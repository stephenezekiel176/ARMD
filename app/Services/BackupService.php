<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupService
{
    protected $enabled;
    protected $disk;
    protected $retentionDays;

    public function __construct()
    {
        $this->enabled = config('backup.enabled', false);
        $this->disk = config('backup.disk', 'local');
        $this->retentionDays = config('backup.retention_days', 30);
    }

    /**
     * Create a full database backup
     */
    public function createDatabaseBackup()
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $path = "backups/{$filename}";

            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Create backup using mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                $username,
                $password,
                $host,
                $database,
                storage_path("app/{$path}")
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                // Clean old backups
                $this->cleanOldBackups();
                
                return [
                    'success' => true,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => Storage::disk($this->disk)->size($path)
                ];
            }

            return ['success' => false, 'error' => 'Backup command failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Create file backup
     */
    public function createFilesBackup()
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "files_backup_{$timestamp}.zip";
            $path = "backups/{$filename}";

            // Create zip of storage/app/public directory
            $zip = new \ZipArchive();
            $zipPath = storage_path("app/{$path}");

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(storage_path('app/public')),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen(storage_path('app/public')) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                $zip->close();

                return [
                    'success' => true,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => Storage::disk($this->disk)->size($path)
                ];
            }

            return ['success' => false, 'error' => 'Failed to create zip file'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Clean old backups based on retention policy
     */
    protected function cleanOldBackups()
    {
        try {
            $backups = Storage::disk($this->disk)->files('backups');
            $cutoffDate = Carbon::now()->subDays($this->retentionDays);

            foreach ($backups as $backup) {
                $lastModified = Carbon::createFromTimestamp(
                    Storage::disk($this->disk)->lastModified($backup)
                );

                if ($lastModified->lt($cutoffDate)) {
                    Storage::disk($this->disk)->delete($backup);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to clean old backups: ' . $e->getMessage());
        }
    }

    /**
     * Get list of available backups
     */
    public function getBackupList()
    {
        if (!$this->enabled) {
            return [];
        }

        try {
            $backups = Storage::disk($this->disk)->files('backups');
            $backupList = [];

            foreach ($backups as $backup) {
                $backupList[] = [
                    'filename' => basename($backup),
                    'path' => $backup,
                    'size' => Storage::disk($this->disk)->size($backup),
                    'created_at' => Carbon::createFromTimestamp(
                        Storage::disk($this->disk)->lastModified($backup)
                    )
                ];
            }

            return collect($backupList)->sortByDesc('created_at')->values();
        } catch (\Exception $e) {
            \Log::error('Failed to get backup list: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Download a backup file
     */
    public function downloadBackup($filename)
    {
        if (!$this->enabled) {
            return false;
        }

        $path = "backups/{$filename}";
        
        if (!Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->download($path);
    }

    /**
     * Delete a backup file
     */
    public function deleteBackup($filename)
    {
        if (!$this->enabled) {
            return false;
        }

        $path = "backups/{$filename}";
        
        if (!Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }
}
