<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'icon' => 'badge-' . fake()->slug(1) . '.svg',
            'criteria' => json_encode([
                'points_required' => fake()->numberBetween(100, 1000),
                'courses_completed' => fake()->numberBetween(1, 10),
                'min_score' => fake()->numberBetween(70, 95),
            ]),
        ];
    }
}