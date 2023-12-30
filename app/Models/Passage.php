<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passage extends Model
{
  use HasFactory;
  protected $guarded = [];
  static function createRule()
  {
    return [
      'from' => ['required', 'integer'],
      'to' => ['required', 'integer', 'gte:from'],
      'passage' => ['required', 'string']
    ];
  }

  static function multiInsert(CourseSession $courseSession, array $passages)
  {
    foreach ($passages as $key => $passage) {
      $courseSession->passages()->firstOrCreate(
        [
          'from' => $passage['from_'],
          'to' => $passage['to_']
        ],
        ['passage' => $passage['passage']]
      );
    }
  }

  function session()
  {
    return $this->belongsTo(CourseSession::class, 'course_session_id', 'id');
  }
}
