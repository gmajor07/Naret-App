<?php

namespace App\Services;

use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class DatabaseBackupService
{
    /**
     * Create a timestamped MySQL backup and remove backups older than the retention period.
     *
     * @return array{path: string, filename: string, deleted: int}
     */
    public function create(int $retentionDays = 30): array
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}");

        if (! $database || ($database['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('Database backup is currently supported for MySQL only.');
        }

        $dumpBinary = config('database.backup.binary')
            ?: (PHP_OS_FAMILY === 'Darwin' ? '/Applications/XAMPP/xamppfiles/bin/mysqldump' : 'mysqldump');

        if (is_string($dumpBinary) && Str::startsWith($dumpBinary, '/') && ! is_executable($dumpBinary)) {
            $dumpBinary = 'mysqldump';
        }

        $backupDirectory = config('database.backup.path') ?: $this->defaultBackupDirectory();

        if (! is_dir($backupDirectory) && ! mkdir($backupDirectory, 0750, true) && ! is_dir($backupDirectory)) {
            throw new RuntimeException('Could not create the database backup folder.');
        }

        $filename = 'naret_database_backup_' . now()->format('Y-m-d_H-i-s') . '_' . uniqid() . '.sql';
        $backupPath = $backupDirectory . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($backupPath, 'wb');

        if ($handle === false) {
            throw new RuntimeException('Could not open the database backup file.');
        }

        $process = new Process([
            $dumpBinary,
            '--host=' . ($database['host'] ?? '127.0.0.1'),
            '--port=' . ($database['port'] ?? 3306),
            '--user=' . ($database['username'] ?? ''),
            '--single-transaction',
            '--triggers',
            $database['database'] ?? '',
        ], base_path(), [
            'MYSQL_PWD' => (string) ($database['password'] ?? ''),
        ]);

        $exitCode = $process->run(function (string $type, string $buffer) use ($handle): void {
            if ($type === Process::OUT) {
                fwrite($handle, $buffer);
            }
        });
        fclose($handle);

        if ($exitCode !== 0) {
            @unlink($backupPath);
            throw new RuntimeException('Database backup failed: ' . trim($process->getErrorOutput()));
        }

        return [
            'path' => $backupPath,
            'filename' => $filename,
            'deleted' => $this->deleteExpired($backupDirectory, max(1, $retentionDays)),
        ];
    }

    private function defaultBackupDirectory(): string
    {
        $homeDirectory = getenv('HOME')
            ?: getenv('USERPROFILE')
            ?: (getenv('HOMEDRIVE') && getenv('HOMEPATH') ? getenv('HOMEDRIVE') . getenv('HOMEPATH') : null)
            ?: ($_SERVER['HOME'] ?? null);

        return $homeDirectory
            ? $homeDirectory . DIRECTORY_SEPARATOR . 'Documents' . DIRECTORY_SEPARATOR . 'Naret Database Backups'
            : storage_path('app/backups');
    }

    private function deleteExpired(string $directory, int $retentionDays): int
    {
        $cutoff = now()->subDays($retentionDays)->getTimestamp();
        $deleted = 0;

        foreach (glob($directory . DIRECTORY_SEPARATOR . 'naret_database_backup_*.sql') ?: [] as $path) {
            if (is_file($path) && filemtime($path) < $cutoff && @unlink($path)) {
                $deleted++;
            }
        }

        return $deleted;
    }
}
