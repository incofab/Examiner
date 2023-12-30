<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
  use HasFactory;

  protected $guarded = [];

  static function createRule()
  {
    return [
      'from' => ['required', 'integer'],
      'to' => ['required', 'integer', 'gte:from'],
      'instruction' => ['required', 'string']
    ];
  }

  static function multiInsert(CourseSession $courseSession, array $instructions)
  {
    foreach ($instructions as $key => $instruction) {
      $courseSession->instructions()->firstOrCreate(
        [
          'from' => $instruction['from_'],
          'to' => $instruction['to_']
        ],
        ['instruction' => $instruction['instruction']]
      );
    }
  }
}
