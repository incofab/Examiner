<?php
namespace App\Support;

use App\Actions\RetrieveSubjectDetails;
use App\Enums\ExamStatus;
use App\Helpers\ExamAttemptFileHandler;
use App\Jobs\UploadExamResultJob;
use App\Models\Exam;
use DB;

class ExamHandler
{
  function __construct(private Exam $exam)
  {
    (new RetrieveSubjectDetails($exam))->run();
  }

  static function make(Exam $exam)
  {
    return new self($exam);
  }

  function canRun(bool $canAbort = true)
  {
    if ($this->isPending()) {
      return true;
    }
    if ($this->isPaused()) {
      return true;
    }
    if ($this->isEnded()) {
      return false;
    }

    // Getting her means its active
    if ($this->hasSomeTimeRemaining()) {
      return true;
    }
    if ($canAbort) {
      $this->endAndAbort('Time Elapsed');
    }
    return false;
  }

  function startExam()
  {
    abort_unless($this->canRun(true), 403, 'Exam cannot be started');

    if ($this->isStarted()) {
      return $this;
    }

    $duration = $this->isPaused()
      ? $this->exam->time_remaining
      : $this->exam->duration; //gets the duration in seconds

    $this->exam
      ->fill([
        'start_time' => $this->exam->start_time ?? now(), //Maintain original start_time
        'pause_time' => null,
        'end_time' => now()->addSecond($duration),
        'time_remaining' => null,
        'status' => ExamStatus::Active
      ])
      ->save();

    ExamAttemptFileHandler::make(
      $this->exam->only([
        'id',
        'exam_no',
        'platform',
        'time_remaining',
        'start_time',
        'pause_time',
        'end_time',
        'status',
        'num_of_questions'
      ])
    )->syncExamFile();

    return $this;
  }

  function pauseExam()
  {
    if ($this->isEnded() || $this->isPaused()) {
      return;
    }

    if (!$this->hasSomeTimeRemaining()) {
      $this->endAndAbort('Time has elapsed');
      return;
    }

    $timeRemaining = now()->diffInSeconds($this->exam->end_time, true);

    $this->exam
      ->fill([
        'pause_time' => now(),
        'end_time' => null,
        'time_remaining' => $timeRemaining,
        'status' => ExamStatus::Paused
      ])
      ->save();
  }

  function endExam()
  {
    if ($this->isEnded()) {
      return;
    }
    $examAttemptFileHandler = ExamAttemptFileHandler::make($this->exam);
    $totalScorePercent = 0;
    $totalScore = 0;
    $totalNumOfQuestions = 0;
    $totalNumOfQuestionsPercent = 0;
    DB::beginTransaction();

    foreach ($this->exam->examItems as $key => $examItem) {
      $questions = $examItem->course_session->questions;
      $scoreCalc = $examAttemptFileHandler->calculateScoreFromFile(
        (array) $questions
      );
      $score = $scoreCalc['score'] ?? $examItem->score;
      $numOfQuestions = count($questions);
      $examItem->safeUpdate([
        'score' => $score,
        'num_of_questions' => $numOfQuestions
      ]);
      $totalScore += $score;
      $totalNumOfQuestions += $numOfQuestions;
      $totalScorePercent +=
        $numOfQuestions > 0 ? round(($score / $numOfQuestions) * 100, 2) : 0;
      $totalNumOfQuestionsPercent += 100;
    }

    $this->exam
      ->fill([
        'pause_time' => null,
        'end_time' => null,
        'time_remaining' => 0,
        'status' => ExamStatus::Ended,
        'score' => $totalScorePercent, //$totalScore,
        'num_of_questions' => $totalNumOfQuestionsPercent, //$totalNumOfQuestions,
        'attempts' => $examAttemptFileHandler->getQuestionAttempts()
      ])
      ->save();
    DB::commit();
    $examAttemptFileHandler->deleteExamFile();
    RetrieveSubjectDetails::removeCache($this->exam);
    dispatch(new UploadExamResultJob($this->exam));
  }

  function endAndAbort($reason = null)
  {
    $this->endExam();
    abort(403, $reason ?? 'Exam has ended');
  }

  function hasSomeTimeRemaining()
  {
    $timeRemaining = $this->getTimeRemaining();
    return $timeRemaining > 5;
  }

  function getTimeRemaining()
  {
    return now()->diffInSeconds($this->exam->end_time, false);
  }

  function isPending()
  {
    return $this->exam->status === ExamStatus::Pending;
  }

  function isPaused()
  {
    return $this->exam->status === ExamStatus::Paused;
  }

  function isStarted()
  {
    return $this->exam->status === ExamStatus::Active;
  }

  function isEnded()
  {
    return $this->exam->status === ExamStatus::Ended;
  }
}
