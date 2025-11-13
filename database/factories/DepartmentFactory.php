<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            [
                'name' => 'Engineering',
                'description' => 'The Engineering department is the technological backbone of Atommart. We specialize in developing cutting-edge software solutions, maintaining robust infrastructure, and implementing innovative technical solutions.',
                'slogan' => 'Building Tomorrow\'s Solutions Today',
                'functionalities' => json_encode([
                    'Software Development & Architecture',
                    'Cloud Infrastructure Management',
                    'System Integration & Optimization',
                    'Technical Innovation'
                ]),
                'impact_statement' => 'Driving technological excellence and innovation across Atommart\'s digital landscape.'
            ],
            [
                'name' => 'Marketing',
                'description' => 'Our Marketing department is the creative force behind Atommart\'s market presence. We transform product features into compelling stories and build lasting relationships with customers.',
                'slogan' => 'Crafting Stories, Building Brands',
                'functionalities' => json_encode([
                    'Digital Marketing & Analytics',
                    'Brand Development & Management',
                    'Market Research & Strategy',
                    'Customer Engagement'
                ]),
                'impact_statement' => 'Creating meaningful connections between our products and people worldwide.'
            ],
            [
                'name' => 'Research & Development',
                'description' => 'The R&D department is Atommart\'s innovation powerhouse. We explore emerging technologies, develop groundbreaking products, and conduct research that shapes the future.',
                'slogan' => 'Innovating for a Better Tomorrow',
                'functionalities' => json_encode([
                    'Product Innovation & Development',
                    'Technology Research & Analysis',
                    'Market Trend Analysis',
                    'Future Technologies Exploration'
                ]),
                'impact_statement' => 'Shaping the future through groundbreaking research and innovation.'
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Our HR department is dedicated to nurturing talent and fostering a positive workplace culture. We ensure Atommart remains a great place to work and grow.',
                'slogan' => 'Empowering People, Enabling Growth',
                'functionalities' => json_encode([
                    'Talent Acquisition & Development',
                    'Employee Engagement',
                    'Performance Management',
                    'Cultural Development'
                ]),
                'impact_statement' => 'Building and nurturing the human capital that drives Atommart\'s success.'
            ],
        ];

        $dept = fake()->unique()->randomElement($departments);

        return [
            'name' => $dept['name'],
            'special_code' => substr($dept['name'], 0, 3) . date('Y'),
            'description' => $dept['description'],
            'slogan' => $dept['slogan'],
            'functionalities' => $dept['functionalities'],
            'impact_statement' => $dept['impact_statement'],
        ];
    }
}
