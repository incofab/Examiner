<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseSessionFactory extends Factory
{
  public function definition(): array
  {
    $sessions = ['2001', '2002', '2003', '2004', '2005', '2006'];
    return [
      'course_id' => fake()
        ->unique()
        ->randomDigitNotZero(), //Course::factory()->make(),
      'category' => '',
      'session' => $this->faker->randomElement($sessions)
    ];
  }
  public function course(Course $course): static
  {
    return $this->state(fn(array $attributes) => ['course_id' => $course]);
  }
}
