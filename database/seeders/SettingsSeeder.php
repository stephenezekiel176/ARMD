<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default help email setting
        Setting::updateOrCreate(
            ['key' => 'help_email'],
            [
                'value' => 'support@atommart.com',
                'type' => 'email',
                'description' => 'Email address for user support requests'
            ]
        );

        $this->command->info('✅ Default settings created successfully!');
    }
}
