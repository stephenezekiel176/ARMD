<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\Submission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the production database with clean data
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear all existing data
        $this->command->info('Clearing existing data...');
        
        // Clear in order of dependencies
        Submission::truncate();
        Enrollment::truncate();
        Assessment::truncate();
        Course::truncate();
        User::truncate();
        Department::truncate();
        Badge::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('All existing data cleared successfully!');
        
        // Create achievement badges
        $this->command->info('Creating achievement badges...');
        
        Badge::create([
            'name' => 'Quick Starter',
            'description' => 'Completed first course with minimum 70% score',
            'icon' => 'badges/quick-starter.svg',
            'criteria' => json_encode([
                'courses_completed' => 1,
                'min_score' => 70
            ]),
        ]);

        Badge::create([
            'name' => 'Department Expert',
            'description' => 'Mastered all courses in a department',
            'icon' => 'badges/department-expert.svg',
            'criteria' => json_encode([
                'department_courses_completed' => 1,
                'min_avg_score' => 85
            ]),
        ]);

        Badge::create([
            'name' => 'High Performer',
            'description' => 'Achieved 90% or higher in any assessment',
            'icon' => 'badges/high-performer.svg',
            'criteria' => json_encode([
                'single_assessment_score' => 90
            ]),
        ]);

        Badge::create([
            'name' => 'Consistent Learner',
            'description' => 'Completed 5 or more courses',
            'icon' => 'badges/consistent-learner.svg',
            'criteria' => json_encode([
                'courses_completed' => 5
            ]),
        ]);

        $this->command->info('Achievement badges created!');
        
        // Create default department for admin
        $this->command->info('Creating default department for admin...');
        
        $adminDepartment = Department::create([
            'name' => 'System Administration',
            'special_code' => 'SYS2025',
            'description' => 'System administration department for managing the LMS platform and overseeing all organizational operations.',
        ]);
        
        // Create production admin user
        $this->command->info('Creating production admin user...');
        
        $admin = User::create([
            'fullname' => 'Production Administrator',
            'email' => 'admin@atommart.com',
            'password' => Hash::make('ADMIN2025!@#'), // Strong production password
            'role' => 'secondary_admin',
            'position' => 'System Administrator',
            'department_id' => $adminDepartment->id,
            'points' => 0,
            'badges' => [],
        ]);
        
        // Assign unique avatar to admin
        $admin->assignUniqueAvatar();
        $this->command->info('Admin avatar assigned: ' . $admin->getAvatarDisplayName());
        
        $this->command->info('Admin user created: admin@atommart.com / ADMIN2025!@#');
        
        $this->command->info('Production setup completed successfully!');
        $this->command->info('');
        $this->command->info('=== PRODUCTION LOGIN CREDENTIALS ===');
        $this->command->info('Admin Email: admin@atommart.com');
        $this->command->info('Admin Password: ADMIN2025!@#');
        $this->command->info('');
        $this->command->info('You can now create departments and users through the admin panel!');
    }
}
