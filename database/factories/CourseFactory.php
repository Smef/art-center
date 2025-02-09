<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->modify('+3 months'));

        return [
            'name' => $this->faker->words(3, true),
            'location_id' => Location::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($course) {
            // Add random number of students (0-5)
            $studentCount = $this->faker->numberBetween(0, 5);
            $students = User::inRandomOrder()->limit($studentCount)->get();
            $course->students()->attach($students);

            // Add random number of teachers (1-2)
            $teacherCount = $this->faker->numberBetween(1, 2);
            $teachers = User::inRandomOrder()->limit($teacherCount)->get();
            $course->teachers()->attach($teachers);
        });
    }

    /**
     * Indicate that the course is in the past.
     */
    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('-1 year', '-1 month');
            $endDate = $this->faker->dateTimeBetween($startDate, $startDate->modify('+3 months'));

            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
        });
    }

    /**
     * Indicate that the course is in the future.
     */
    public function future(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('+1 month', '+1 year');
            $endDate = $this->faker->dateTimeBetween($startDate, $startDate->modify('+3 months'));

            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
        });
    }
}
