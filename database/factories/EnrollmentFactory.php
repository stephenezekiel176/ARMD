<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'personnel']),
            'course_id' => Course::factory(),
            'enrolled_at' => fake()->dateTimeThisYear(),
            'status' => fake()->randomElement(['enrolled', 'in_progress', 'completed']),
            'completed_at' => function (array $attributes) {
                return $attributes['status'] === 'completed' ? fake()->dateTimeThisYear() : null;
            },
            'progress' => function (array $attributes) {
                switch ($attributes['status']) {
                    case 'enrolled':
                        return 0;
                    case 'in_progress':
                        return fake()->numberBetween(1, 99);
                    case 'completed':
                        return 100;
                    default:
                        return 0;
                }
            }
        ];
    }

    /**
     * Indicate that the enrollment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => fake()->dateTimeThisYear(),
            'progress' => 100,
        ]);
    }

    /**
     * Indicate that the enrollment is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'completed_at' => null,
            'progress' => fake()->numberBetween(1, 99),
        ]);
    }
}
