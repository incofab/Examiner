<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Actions\RetrieveSubjectDetails;
use App\Helpers\ExamAttemptFileHandler;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Support\ExamHandler;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisplayExamPageController extends Controller
{
  function __invoke(Exam $exam, Request $request)
  {
    abort_unless($request->hasValidSignature(), 403, 'Invalid signature');
    $exam->load('examItems');

    // Connects to the content protal, then Retrieve and assigns the subject details to exam items
    (new RetrieveSubjectDetails($exam))->run();

    $examHandler = ExamHandler::make($exam)->startExam();
    $examAttemptFileHandler = ExamAttemptFileHandler::make($exam);

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
