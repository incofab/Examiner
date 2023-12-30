<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Support\ExamHandler;
use Illuminate\Http\Request;

class PauseExamController extends Controller
{
  function __invoke(Exam $exam, Request $request)
  {
    $examHandler = ExamHandler::make($exam);
    if ($examHandler->canRun()) {
      $examHandler->pauseExam();
    }

    return $this->ok();
  }
}
