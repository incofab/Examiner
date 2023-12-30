<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Actions\RetrieveSubjectDetails;
use App\Enums\ExamStatus;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Support\ExamHandler;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamResultController extends Controller
{
  function __invoke(Exam $exam, Request $request)
  {
    $exam->load('examItems');

    $examHandler = ExamHandler::make($exam);

    abort_if(
      $examHandler->canRun(false),
      403,
      'You cannot view results when exam is still active'
    );

    (new RetrieveSubjectDetails($exam))->run();

    if ($exam->status !== ExamStatus::Ended) {
      $examHandler->endExam();
    }

    return Inertia::render('exams/exam-page/exam-result', [
      'exam' => $exam,
      'user' => $request->user
    ]);
  }
}
