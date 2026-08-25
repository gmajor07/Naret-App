<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--retention= : Number of days to keep old backups}';

    protected $description = 'Create a timestamped database backup and remove expired backups';

    public function handle(DatabaseBackupService $backupService): int
    {
        try {
            $retentionDays = (int) ($this->option('retention') ?: config('database.backup.retention_days', 30));
            $backup = $backupService->create($retentionDays);

            $this->info("Database backup created: {$backup['filename']}");
            if ($backup['deleted'] > 0) {
                $this->line("Expired backups removed: {$backup['deleted']}");
            }

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
