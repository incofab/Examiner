<?php

namespace App\Models;

use App\Enums\ExamStatus;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
  use HasFactory;

  public $guarded = [];

  public $casts = [
    'status' => ExamStatus::class,
    'start_time' => 'datetime',
    'pause_time' => 'datetime',
    'end_time' => 'datetime',
    'meta' => AsArrayObject::class,
    'attempts' => AsArrayObject::class
  ];

  static function platformExamNo($platform, $examNo)
  {
    return "{$platform}-{$examNo}";
  }

  function examItems()
  {
    return $this->hasMany(ExamItem::class);
  }
}
