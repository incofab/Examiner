<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  use HasFactory;

  function sessions()
  {
    return $this->hasMany(CourseSession::class);
  }

  function topics()
  {
    return $this->hasMany(Topic::class);
  }

  function summaries()
  {
    return $this->hasMany(Summary::class);
  }
}
