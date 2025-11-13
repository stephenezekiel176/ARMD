<?php

namespace App\Console\Commands;

use App\Services\AvatarService;
use Illuminate\Console\Command;
use App\Models\User;

class InitializeAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatars:initialize {--force : Overwrite existing avatars}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize unique avatars for all users without avatars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎭 Initializing Unique Avatar System');
        $this->info('=====================================');

        // Get avatar statistics
        $stats = AvatarService::getAvatarStats();
        
        $this->info("📊 Current Avatar Statistics:");
        $this->info("   Total Available Avatars: {$stats['total_avatars']}");
        $this->info("   Used Avatars: {$stats['used_avatars']}");
        $this->info("   Available Avatars: {$stats['available_avatars']}");
        $this->info("   Generated Avatars: {$stats['generated_avatars']}");
        
        if (!empty($stats['used_by_category'])) {
            $this->info("\n📈 Usage by Category:");
            foreach ($stats['used_by_category'] as $category => $count) {
                $this->info("   " . ucfirst($category) . ": {$count}");
            }
        }

        // Get users without avatars
        $query = User::whereNull('avatar');
        
        if (!$this->option('force')) {
            $usersWithoutAvatar = $query->get();
            $count = $usersWithoutAvatar->count();
            
            if ($count === 0) {
                $this->info("\n✅ All users already have avatars!");
                return 0;
            }
            
            $this->info("\n👥 Found {$count} users without avatars");
            
            if (!$this->confirm('Proceed with assigning unique avatars to these users?')) {
                $this->info('❌ Avatar initialization cancelled');
                return 0;
            }
        } else {
            // Force mode - clear all avatars first
            $this->info('\n🔄 Force mode: Clearing existing avatars...');
            User::whereNotNull('avatar')->update(['avatar' => null]);
            $this->info('✅ Existing avatars cleared');
        }

        // Initialize avatars
        $this->info('\n🎨 Assigning unique avatars...');
        
        $progressBar = $this->output->createProgressBar($query->count());
        $progressBar->start();
        
        $assignedCount = 0;
        $query->chunk(100, function ($users) use (&$assignedCount, $progressBar) {
            foreach ($users as $user) {
                AvatarService::assignUniqueAvatar($user);
                $assignedCount++;
                $progressBar->advance();
            }
        });
        
        $progressBar->finish();
        
        // Show final statistics
        $finalStats = AvatarService::getAvatarStats();
        
        $this->info("\n\n✅ Avatar initialization completed!");
        $this->info("   Avatars Assigned: {$assignedCount}");
        $this->info("   Total Users with Avatars: {$finalStats['used_avatars']}");
        $this->info("   Remaining Available Avatars: {$finalStats['available_avatars']}");
        
        if ($finalStats['available_avatars'] < 10) {
            $this->warn("\n⚠️  Warning: Less than 10 avatars remaining!");
            $this->warn("   Consider adding more avatar styles or enable generated avatars.");
        }
        
        $this->info("\n🎉 All users now have unique avatars!");
        
        return 0;
    }
}
