<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * Seed the application's database with development data.
     */
    public function run(): void
    {
        // Create departments with detailed purposes and functions
        $departments = [
            'eng' => Department::firstOrCreate(
                ['special_code' => 'ENG2025'],
                [
                    'name' => 'Engineering',
                    'description' => 'The Engineering department at AtomMart is the cornerstone of our technological advancement. We focus on developing cutting-edge software solutions, maintaining robust infrastructure, and implementing innovative technical solutions. Our team specializes in full-stack development, cloud architecture, and systems integration to drive the company\'s digital transformation.',
                ]
            ),
            'mkt' => Department::firstOrCreate(
                ['special_code' => 'MKT2025'],
                [
                    'name' => 'Marketing',
                    'description' => 'The Marketing department drives AtomMart\'s brand visibility and market presence. We specialize in creating compelling digital marketing strategies, managing social media campaigns, and developing brand identity. Our team analyzes market trends, conducts customer research, and implements data-driven marketing solutions to expand our market reach and enhance customer engagement.',
                ]
            ),
            'rnd' => Department::firstOrCreate(
                ['special_code' => 'RND2025'],
                [
                    'name' => 'Research & Development',
                    'description' => 'The R&D department is AtomMart\'s innovation hub, established to keep us at the forefront of technological advancement. We conduct extensive market research, develop new product concepts, and explore emerging technologies. Our team combines scientific methodology with creative problem-solving to transform ideas into viable products that address real market needs.',
                ]
            )
        ];

        // Create system admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@atommart.com'],
            [
                'fullname' => 'System Administrator',
                'department_id' => $departments['eng']->id,
                'position' => 'Chief Technology Officer',
                'role' => 'secondary_admin',
                'password' => Hash::make('ADMIN2025'), // Admin access code
                'points' => 0,
                'badges' => [],
            ]
        );
        
        // Assign avatar if new user was created
        if ($admin->wasRecentlyCreated) {
            $admin->assignUniqueAvatar();
        }

        // Create facilitators for each department with their department code as access code
        foreach ($departments as $code => $dept) {
            $facilitator = User::firstOrCreate(
                ['email' => "facilitator.{$dept->special_code}@atommart.com"],
                [
                    'fullname' => "{$dept->name} Lead Facilitator",
                    'department_id' => $dept->id,
                    'position' => 'Department Lead',
                    'role' => 'facilitator',
                    'password' => Hash::make($dept->special_code), // Department code as access code
                    'points' => 0,
                    'badges' => [],
                ]
            );
            
            // Assign avatar if new user was created
            if ($facilitator->wasRecentlyCreated) {
                $facilitator->assignUniqueAvatar();
            }
        }

        // Create achievement badges
        Badge::firstOrCreate(
            ['name' => 'Quick Starter'],
            [
                'description' => 'Completed first course with minimum 70% score',
                'icon' => 'badges/quick-starter.svg',
                'criteria' => json_encode([
                    'courses_completed' => 1,
                    'min_score' => 70
                ]),
            ]
        );

        Badge::firstOrCreate(
            ['name' => 'Department Expert'],
            [
                'description' => 'Mastered all courses in a department',
                'icon' => 'badges/department-expert.svg',
                'criteria' => json_encode([
                    'department_courses_completed' => 1,
                    'min_avg_score' => 85
                ]),
            ]
        );

        // Create core courses for each department
        foreach ($departments as $dept) {
            // Create one initial course per department
            Course::firstOrCreate(
                ['title' => "Introduction to {$dept->name}"],
                [
                    'type' => 'ebook',
                    'description' => "Essential introduction to {$dept->name} principles and practices.",
                    'department_id' => $dept->id,
                    'facilitator_id' => User::where('department_id', $dept->id)
                        ->where('role', 'facilitator')
                        ->first()
                        ->id,
                    'duration' => 60,
                    'is_previewable' => true,
                    'file_path' => "courses/{$dept->name}/introduction.pdf",
            ]);
        }
        
        $this->command->info('Development data seeded successfully!');
        $this->command->info('');
        $this->command->info('=== DEVELOPMENT LOGIN CREDENTIALS ===');
        $this->command->info('Admin Email: admin@atommart.com');
        $this->command->info('Admin Password: ADMIN2025');
        $this->command->info('');
        $this->command->info('Facilitator Emails:');
        foreach ($departments as $dept) {
            $this->command->info("- facilitator.{$dept->special_code}@atommart.com / {$dept->special_code}");
        }
    }
}
