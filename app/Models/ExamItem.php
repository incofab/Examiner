<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $num_of_questions
 * @property float $score
 * @property float $status
 * @property CourseSession $course_session
 */
class ExamItem extends Model
{
  use HasFactory;
  protected $fillable = [
    'exam_id',
    'course_session_id',
    'num_of_questions',
    'score',
    'status',
    'shuffle'
  ];

  function safeUpdate(array $dataToUpdate)
  {
    $this->fill($dataToUpdate);
    self::query()
      ->find($this->id)
      ->update($dataToUpdate);
  }
}
