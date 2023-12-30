<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $course_id
 * @property string $session
 * @property string $general_instructions
 * @property string $category
 * @property Course $course
 * @property Question[] $questions
 * @property Instruction[] $instructions
 * @property Passage[] $passages
 */
class CourseSession extends Model
{
  use HasFactory;

  protected $guarded = [];
}
