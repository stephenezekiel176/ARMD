<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Assessment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
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
            'assessment_id' => Assessment::factory(),
            'responses' => function (array $attributes) {
                $assessment = Assessment::find($attributes['assessment_id']);
                if (!$assessment) {
                    return json_encode([]);
                }

                $questions = json_decode($assessment->questions, true);

                if ($assessment->type === 'quiz') {
                    return json_encode(collect($questions)->map(function($q) {
                        return [
                            'answer' => fake()->numberBetween(0, 3),
                        ];
                    })->toArray());
                } else {
                    return json_encode([
                        'submission_text' => fake()->paragraphs(3, true),
                        'attachments' => [
                            'submission-' . fake()->slug(2) . '.pdf'
                        ]
                    ]);
                }
            },
            'submitted_at' => fake()->dateTimeThisMonth(),
            'graded_at' => function (array $attributes) {
                return fake()->boolean(70) ? fake()->dateTimeThisMonth() : null;
            },
            'score' => function (array $attributes) {
                return $attributes['graded_at'] ? fake()->numberBetween(0, 100) : null;
            },
            'feedback' => function (array $attributes) {
                return $attributes['graded_at'] ? fake()->paragraph() : null;
            },
        ];
    }

    /**
     * Indicate that the submission has been graded.
     */
    public function graded(): static
    {
        return $this->state(fn (array $attributes) => [
            'graded_at' => fake()->dateTimeThisMonth(),
            'score' => fake()->numberBetween(0, 100),
            'feedback' => fake()->paragraph(),
        ]);
    }

    /**
     * Indicate that the submission is pending grading.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'graded_at' => null,
            'score' => null,
            'feedback' => null,
        ]);
    }
}