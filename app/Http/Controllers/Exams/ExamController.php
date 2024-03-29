<?php

namespace App\Http\Controllers\Exams;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Models\Exam;
use App\Support\ExamHandler;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use URL;

class ExamController extends Controller
{
  public function __construct()
  {
  }

  function index(Request $request)
  {
    $query = Exam::query();
    return response()->json(paginateFromRequest($query->latest('id')));
  }

  function store(StoreExamRequest $request)
  {
    DB::beginTransaction();
    $exam = Exam::query()->firstOrCreate(
      ['exam_no' => $request->exam_no],
      $request->safe()->except('subject_details', 'name')
    );
    $examItems = [];
    foreach ($request->subject_details as $key => $item) {
      $examItems[] = $exam
        ->examItems()
        ->firstOrCreate(
          ['course_session_id' => $item['course_session_id']],
          $item
        );
    }
    DB::commit();
    $url = URL::signedRoute('display-exam-page', [
      'exam' => $exam->exam_no,
      'name' => $request->safe()->name
    ]);
    $exam['exam_items'] = $examItems;
    return $this->ok(['exam' => $exam, 'url' => $url]);
  }

  function retrieve(Request $request)
  {
    $request->validate(['exam_no' => ['required', 'exists:exams,exam_no']]);
    $exam = Exam::query()
      ->where('exam_no', $request->exam_no)
      ->with('examItems')
      ->firstOrFail();
    return $this->ok(['exam' => $exam]);
  }

  function terminate(Request $request)
  {
    $request->validate(['exam_no' => ['required', 'exists:exams,exam_no']]);
    $exam = Exam::query()
      ->where('exam_no', $request->exam_no)
      ->with('examItems')
      ->firstOrFail();

    ExamHandler::make($exam)->endExam();
    $exam = $exam->fresh('examItems');
    return $this->ok(['exam' => $exam]);
  }

  function destroy(Exam $exam)
  {
    $exam->delete();
    return $this->ok();
  }

  function examCompletedMessage()
  {
    return Inertia::render('message', [
      'title' => 'Exam Completed Successfully',
      'message' =>
        'Congratulations!!! You have successfully completed the exam and your result have been submitted'
    ]);
  }
}
