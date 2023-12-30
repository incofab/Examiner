<?php

namespace App\Http\Controllers\Exams\ExamPage;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ExamLoginController extends Controller
{
  function __invoke()
  {
    return Inertia::render('exams/exam-page/exam-login', []);
  }
}
