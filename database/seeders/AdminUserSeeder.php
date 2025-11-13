<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default department for testing facilitator and personnel
        $department = Department::firstOrCreate(
            ['special_code' => 'ADMIN'],
            [
                'name' => 'Administration',
                'description' => 'Administrative Department',
                'icon' => 'shield',
                'slogan' => 'Managing Excellence',
            ]
        );

        // Create admin user (secondary_admin role) - NOT assigned to any department
        User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'department_id' => null, // Admin has no department
            'position' => 'System Administrator',
            'role' => 'secondary_admin',
        ]);

        // Create facilitator user - assigned to a department
        User::create([
            'fullname' => 'Facilitator User',
            'email' => 'facilitator@example.com',
            'password' => Hash::make('password123'),
            'department_id' => $department->id,
            'position' => 'Senior Facilitator',
            'role' => 'facilitator',
        ]);

        // Create regular personnel user - assigned to a department
        User::create([
            'fullname' => 'Personnel User',
            'email' => 'personnel@example.com',
            'password' => Hash::make('password123'),
            'department_id' => $department->id,
            'position' => 'Staff Member',
            'role' => 'personnel',
        ]);

        echo "✅ Users created successfully!\n\n";
        echo "Login Credentials:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Admin (Secondary Admin) - No Department:\n";
        echo "  Email: admin@example.com\n";
        echo "  Password: password123\n";
        echo "  Note: Admin has system-wide access\n\n";
        echo "Facilitator - Administration Department:\n";
        echo "  Email: facilitator@example.com\n";
        echo "  Password: password123\n\n";
        echo "Personnel - Administration Department:\n";
        echo "  Email: personnel@example.com\n";
        echo "  Password: password123\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}
