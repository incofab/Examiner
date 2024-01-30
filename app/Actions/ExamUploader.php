<?php
namespace App\Actions;

use App\Models\Exam;
use Http;

class ExamUploader
{
  static function uploadSingle(Exam $exam)
  {
    $url = config("services.platform.upload-exam.{$exam->platform}");
    $res = Http::post($url, [...$exam->toArray(), 'exam_no' => $exam->exam_no]);

    if (!$res->ok()) {
      info('UploadExamResultJob: Error uploading ' . $exam->exam_no);
    }

    $exam
      ->fill([
        'uploaded_at' => $res->ok() ? now() : null,
        'upload_response' => $res->json()
      ])
      ->save();

    return $res->json();
  }

  static function uploadMultiple($exams)
  {
    $res = [];
    foreach ($exams as $key => $exam) {
      $res[] = self::uploadSingle($exam);
    }
    return $res;
  }
}
