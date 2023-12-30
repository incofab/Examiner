<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamItemFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'exam_id' => Exam::factory(),
      'course_session_id' => fake()
        ->unique()
        ->randomDigitNotZero(),
      'score' => fake()->randomNumber(2),
      'num_of_questions' => 30
    ];
  }

  public function exam(Exam $exam): static
  {
    return $this->state(fn(array $attributes) => ['exam_id' => $exam]);
  }
}
