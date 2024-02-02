<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Actions\RetrieveSubjectDetails;
use App\Enums\ExamStatus;
use App\Helpers\ExamAttemptFileHandler;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Support\ExamHandler;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestExamPageController extends Controller
{
  function __invoke(Request $request)
  {
    $exam = Exam::query()
      ->with('examItems')
      ->first();

    $exam
      ->fill([
        'start_time' => now(),
        'pause_time' => null,
        'end_time' => now()->addSecond($exam->duration),
        'time_remaining' => null,
        'status' => ExamStatus::Active
      ])
      ->save();

    // Connects to the content protal, then Retrieve and assigns the subject details to exam items
    (new RetrieveSubjectDetails($exam))->run();

    $examHandler = ExamHandler::make($exam)->startExam();
    $examAttemptFileHandler = ExamAttemptFileHandler::make($exam);
    // dd(json_encode($exam, JSON_PRETTY_PRINT));
    return Inertia::render('exams/exam-page/display-exam', [
      'exam' => $exam,
      'user' => ['full_name' => $request->name],
      'timeRemaining' => $examHandler->getTimeRemaining(),
      'existingAttempts' =>
        collect($exam->attempts)->toArray() +
        $examAttemptFileHandler->getQuestionAttempts()
    ]);
  }
}
