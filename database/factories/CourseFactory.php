<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\User;

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
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['video', 'ebook', 'presentation']),
            'file_path' => 'resources/sample-' . fake()->slug(2) . '.' . fake()->randomElement(['pdf', 'mp4', 'pptx']),
            'department_id' => Department::factory(),
            'facilitator_id' => function (array $attributes) {
                return User::factory()->state(['role' => 'facilitator', 'department_id' => $attributes['department_id']]);
            },
            'duration' => fake()->numberBetween(30, 180),
            'is_previewable' => fake()->boolean(70),
        ];
    }
}
