<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
  public function definition(): array
  {
    $course = fake()
      ->unique()
      ->randomElement(self::COURSES);

    return [
      'title' => $course,
      'code' => $course,
      'category' => $this->faker->word,
      'description' => $this->faker->sentence,
      'is_file_content_uploaded' => false
    ];
  }

  const COURSES = [
    'Engish',
    'Maths',
    'Economics',
    'Biology',
    'Chemistry',
    'Physics',
    'Economics',
    'Agriculture',
    'Introductory Technology',
    'Fine Arts',
    'Computer Science',
    'Integrated Science',
    'Physical and Health Education',
    'Geography',
    'Government',
    'Literature In English',
    'Technical Drawing',
    'French',
    'Arabic',
    'Igbo',
    'Yoruba',
    'Hausa',
    'CRK',
    'IRK',
    'Music'
  ];
}
