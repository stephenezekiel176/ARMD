<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupService;
use Illuminate\Support\Facades\Log;

class CreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create 
                            {--type=full : Type of backup (database, files, full)}
                            {--force : Force backup even if disabled in config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create application backup';

    protected $backupService;

    /**
     * Create a new command instance.
     */
    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting backup process...');

        $type = $this->option('type');
        $force = $this->option('force');

        // Check if backup is enabled
        if (!$force && !config('backup.enabled', false)) {
            $this->error('Backup is disabled in configuration. Use --force to override.');
            return 1;
        }

        $startTime = now();
        $results = [];

        try {
            switch ($type) {
                case 'database':
                    $results['database'] = $this->createDatabaseBackup();
                    break;
                
                case 'files':
                    $results['files'] = $this->createFilesBackup();
                    break;
                
                case 'full':
                default:
                    $results['database'] = $this->createDatabaseBackup();
                    $results['files'] = $this->createFilesBackup();
                    break;
            }

            $duration = now()->diffInSeconds($startTime);
            $this->displayResults($results, $duration);

            // Log backup completion
            Log::info('Backup completed', [
                'type' => $type,
                'duration' => $duration,
                'results' => $results
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            
            Log::error('Backup failed', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }

    /**
     * Create database backup
     */
    protected function createDatabaseBackup()
    {
        $this->line('Creating database backup...');
        
        $result = $this->backupService->createDatabaseBackup();
        
        if ($result['success']) {
            $this->info("✓ Database backup created: {$result['filename']}");
            $this->line("  Size: " . $this->formatBytes($result['size']));
        } else {
            $this->error("✗ Database backup failed: {$result['error']}");
        }
        
        return $result;
    }

    /**
     * Create files backup
     */
    protected function createFilesBackup()
    {
        $this->line('Creating files backup...');
        
        $result = $this->backupService->createFilesBackup();
        
        if ($result['success']) {
            $this->info("✓ Files backup created: {$result['filename']}");
            $this->line("  Size: " . $this->formatBytes($result['size']));
        } else {
            $this->error("✗ Files backup failed: {$result['error']}");
        }
        
        return $result;
    }

    /**
     * Display backup results
     */
    protected function displayResults($results, $duration)
    {
        $this->newLine();
        $this->info('Backup Summary:');
        $this->line("Duration: {$duration} seconds");
        
        foreach ($results as $type => $result) {
            $status = $result['success'] ? '✓ Success' : '✗ Failed';
            $this->line("{$type}: {$status}");
        }
        
        $successful = collect($results)->filter(fn($r) => $r['success'])->count();
        $total = count($results);
        
        $this->newLine();
        if ($successful === $total) {
            $this->info("🎉 All backups completed successfully!");
        } else {
            $this->warn("⚠️  {$successful}/{$total} backups completed successfully");
        }
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
