<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Question extends Model
{
  use HasFactory;
  protected $guarded = [];

  static function createRule(Question $question = null)
  {
    return [
      'question_no' => ['required', 'integer'],
      'question' => ['required', 'string'],
      'option_a' => ['required', 'string'],
      'option_b' => ['required', 'string'],
      'option_c' => ['nullable', 'string'],
      'option_d' => ['nullable', 'string'],
      'option_e' => ['nullable', 'string'],
      'answer' => ['required', 'string'],
      'answer_meta' => ['nullable', 'string'],
      'topic_id' => ['nullable', 'integer', Rule::exists('topics', 'id')]
    ];
  }

  static function multiInsert(CourseSession $courseSession, array $questions)
  {
    $course = $courseSession->course;
    foreach ($questions as $key => $question) {
      $topic = self::handleQuestionTopic($course, $question['topic'] ?? []);
      $courseSession->questions()->firstOrCreate(
        ['question_no' => $question['question_no']],
        [
          ...collect($question)
            ->only([
              'question',
              'option_a',
              'option_b',
              'option_c',
              'option_d',
              'option_e',
              'answer',
              'answer_meta'
            ])
            ->toArray(),
          'topic_id' => $topic?->id
        ]
      );
    }
  }

  private static function handleQuestionTopic(
    Course $course,
    array|null $topicData
  ) {
    if (empty($topicData) || empty($topicData['title'])) {
      return null;
    }
    $topic = $course
      ->topics()
      ->firstOrCreate(['title' => $topicData['title']], $topicData);
    return $topic;
  }

  function topic()
  {
    return $this->belongsTo(Topic::class);
  }
}
