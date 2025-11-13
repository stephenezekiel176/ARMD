<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['quiz', 'assignment']),
            'course_id' => Course::factory(),
            'facilitator_id' => function (array $attributes) {
                $course = Course::find($attributes['course_id']);
                return $course ? $course->facilitator_id : User::factory()->state(['role' => 'facilitator']);
            },
            'questions' => function (array $attributes) {
                if ($attributes['type'] === 'quiz') {
                    return json_encode(collect(range(1, fake()->numberBetween(5, 10)))->map(function() {
                        return [
                            'question' => fake()->sentence() . '?',
                            'options' => collect(range(1, 4))->map(fn() => fake()->word())->toArray(),
                            'correct_answer' => fake()->numberBetween(0, 3),
                            'points' => fake()->randomElement([5, 10, 15, 20])
                        ];
                    })->toArray());
                } else {
                    return json_encode([
                        'instructions' => fake()->paragraph(),
                        'requirements' => collect(range(1, fake()->numberBetween(3, 5)))
                            ->map(fn() => fake()->sentence())
                            ->toArray(),
                        'total_points' => fake()->randomElement([50, 75, 100])
                    ]);
                }
            },
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'duration' => fake()->numberBetween(30, 120),
        ];
    }
}
