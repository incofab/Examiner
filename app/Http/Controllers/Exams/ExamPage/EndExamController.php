<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Support\ExamHandler;
use Illuminate\Http\Request;

class EndExamController extends Controller
{
  function __invoke(Exam $exam, Request $request)
  {
    $exam->load('examItems');
    ExamHandler::make($exam)->endExam();

    return $this->ok();
  }
}
