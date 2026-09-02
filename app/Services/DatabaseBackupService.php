<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DatabaseBackupService
{
    /**
     * Create an SQL dump of the database.
     *
     * @param string|null $database Specific database name (optional)
     * @param string|null $outputPath Optional destination path
     * @return string Full path to the generated .sql backup file
     */
    public function dumpDatabase(?string $database = null, ?string $outputPath = null): string
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $dbConnection = config('database.default', 'mysql');
        $dbConfig = config("database.connections.{$dbConnection}");

        $host = $dbConfig['host'] ?? '127.0.0.1';
        $port = $dbConfig['port'] ?? 3306;
        $database = $database ?? ($dbConfig['database'] ?? 'auditors');
        $username = $dbConfig['username'] ?? '';
        $password = $dbConfig['password'] ?? '';

        $filename = 'backup_' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = $outputPath ?? ($backupDir . '/' . $filename);

        // Attempt 1: Native mysqldump CLI
        if ($this->hasMysqldump() && $dbConnection === 'mysql') {
            try {
                $this->dumpWithMysqldump($host, $port, $database, $username, $password, $fullPath);
                if (file_exists($fullPath) && filesize($fullPath) > 0) {
                    Log::info("Database dump created successfully via mysqldump: {$fullPath} (" . filesize($fullPath) . " bytes)");
                    return $fullPath;
                }
            } catch (\Throwable $e) {
                Log::warning("mysqldump failed, falling back to PHP PDO dumper: " . $e->getMessage());
            }
        }

        // Attempt 2: PHP PDO fallback dumper
        $this->dumpWithPdo($fullPath);
        Log::info("Database dump created successfully via PDO: {$fullPath} (" . filesize($fullPath) . " bytes)");

        return $fullPath;
    }

    private function hasMysqldump(): bool
    {
        $process = Process::fromShellCommandline('which mysqldump');
        $process->run();
        return $process->isSuccessful();
    }

    private function dumpWithMysqldump(string $host, int|string $port, string $database, string $username, string $password, string $outputSqlPath): void
    {
        $command = sprintf(
            'MYSQL_PWD=%s mysqldump --host=%s --port=%s --user=%s --single-transaction --quick --skip-lock-tables --default-character-set=utf8mb4 %s > %s',
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($outputSqlPath)
        );

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('mysqldump error: ' . $process->getErrorOutput());
        }
    }

    private function dumpWithPdo(string $outputSqlPath): void
    {
        $file = fopen($outputSqlPath, 'w');
        if (!$file) {
            throw new \RuntimeException("Could not create SQL file: {$outputSqlPath}");
        }

        $pdo = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();

        fwrite($file, "-- Auditors3.lv Database Dump (PDO fallback)\n");
        fwrite($file, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($file, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
        fwrite($file, "SET time_zone = \"+00:00\";\n\n");

        $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // Table structure
            $createTableStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createTableStmt['Create Table'] ?? '';

            fwrite($file, "\n-- --------------------------------------------------------\n");
            fwrite($file, "-- Table structure for `{$table}`\n");
            fwrite($file, "-- --------------------------------------------------------\n");
            fwrite($file, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($file, $createSql . ";\n\n");

            // Table data
            $rowsStmt = $pdo->query("SELECT * FROM `{$table}`");
            $rowsCount = 0;

            while ($row = $rowsStmt->fetch(\PDO::FETCH_ASSOC)) {
                if ($rowsCount === 0) {
                    fwrite($file, "-- Dumping data for table `{$table}`\n");
                }

                $columns = array_map(fn($col) => "`{$col}`", array_keys($row));
                $values = array_map(function ($val) use ($pdo) {
                    if (is_null($val)) {
                        return 'NULL';
                    }
                    return $pdo->quote($val);
                }, array_values($row));

                $insertSql = sprintf(
                    "INSERT INTO `%s` (%s) VALUES (%s);\n",
                    $table,
                    implode(', ', $columns),
                    implode(', ', $values)
                );

                fwrite($file, $insertSql);
                $rowsCount++;
            }
        }

        fwrite($file, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        fclose($file);
    }
}
