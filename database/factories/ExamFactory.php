<?php

namespace Database\Factories;

use App\Enums\ExamStatus;
use App\Enums\Platform;
use App\Models\Exam;
use App\Models\ExamItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'platform' => Platform::Examscholars->value,
      'exam_no' => fake()
        ->unique()
        ->numerify('###########'),
      'num_of_questions' => 40,
      'status' => ExamStatus::Pending,
      'time_remaining' => 0,
      'duration' => 30,
      'start_time' => now(),
      'pause_time' => null,
      'end_time' => now()->addMinutes(60)
    ];
  }

  public function examItems(int $count = 1): static
  {
    return $this->afterCreating(
      fn(Exam $exam) => ExamItem::factory($count)
        ->exam($exam)
        ->create()
    );
  }

  public function started(): static
  {
    return $this->state(
      fn(array $attributes) => [
        'status' => ExamStatus::Active,
        'time_remaining' => 0,
        'start_time' => now(),
        'pause_time' => null,
        'end_time' => now()->addMinutes(60)
      ]
    );
  }

  public function paused(): static
  {
    return $this->state(
      fn(array $attributes) => [
        'status' => ExamStatus::Paused,
        'time_remaining' => 30 * 60,
        'start_time' => null,
        'pause_time' => now(),
        'end_time' => null
      ]
    );
  }

  public function ended(): static
  {
    return $this->state(
      fn(array $attributes) => [
        'status' => ExamStatus::Ended,
        'time_remaining' => 0,
        'start_time' => now()->subMinutes(30),
        'pause_time' => null,
        'end_time' => now()
      ]
    );
  }

  public function status(ExamStatus $examStatus = ExamStatus::Pending): static
  {
    return $this->state(fn(array $attributes) => ['status' => $examStatus]);
  }
}
