<?php
namespace App\Support;

class MorphMap
{
  static function key($value): string|null
  {
    return array_search($value, self::MAP);
  }

  static function keys(array $values): array
  {
    $keys = [];
    foreach ($values as $key => $value) {
      if ($searchKey = array_search($value, self::MAP)) {
        $keys[] = $searchKey;
      }
    }
    return $keys;
  }

  function value($key): string|null
  {
    return self::MAP[$key] ?? null;
  }

  const MAP = [
    'user' => \App\Models\User::class,
    'exam' => \App\Models\Exam::class,
    'exam-item' => \App\Models\ExamItem::class,
    'course' => \App\Models\Course::class,
    'course-session' => \App\Models\CourseSession::class,
    'question' => \App\Models\Question::class,
    'passage' => \App\Models\Passage::class,
    'instruction' => \App\Models\Instruction::class
  ];
}
