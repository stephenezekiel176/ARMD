<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls the backup functionality for your application.
    | You can enable/disable backups and configure retention policies.
    |
    */

    'enabled' => env('BACKUP_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Backup Disk
    |--------------------------------------------------------------------------
    |
    | The disk where backups will be stored. This should match a disk
    | defined in your filesystems configuration.
    |
    */

    'disk' => env('BACKUP_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Backup Schedule
    |--------------------------------------------------------------------------
    |
    | The cron schedule for automatic backups. Default is daily at 2 AM.
    | Format: minute hour day month day-of-week
    |
    */

    'schedule' => env('BACKUP_SCHEDULE', '0 2 * * *'),

    /*
    |--------------------------------------------------------------------------
    | Backup Retention
    |--------------------------------------------------------------------------
    |
    | Number of days to keep backups. Older backups will be automatically
    | deleted during the cleanup process.
    |
    */

    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Backup Types
    |--------------------------------------------------------------------------
    |
    | Types of backups to create. Available options:
    | - database: MySQL database backup
    | - files: Upload files and user content
    | - full: Both database and files
    |
    */

    'types' => [
        'database' => true,
        'files' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Notifications
    |--------------------------------------------------------------------------
    |
    | Configure email notifications for backup success/failure.
    |
    */

    'notifications' => [
        'enabled' => false,
        'email' => env('BACKUP_NOTIFICATION_EMAIL'),
        'on_success' => true,
        'on_failure' => true,
    ],

];
