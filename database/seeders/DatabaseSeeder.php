<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('=== ATOMMART LMS DATABASE SEEDER ===');
        $this->command->info('');
        $this->command->info('Choose seeder type:');
        $this->command->info('1. Development Seeder (with sample data)');
        $this->command->info('2. Production Seeder (clean setup for production)');
        $this->command->info('');
        
        // For now, run the production seeder by default
        // You can change this to run the development seeder if needed
        $this->call(ProductionSeeder::class);
        
        // Uncomment the line below and comment the line above for development data
        // $this->call(DevelopmentSeeder::class);
    }
}
