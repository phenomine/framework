<?php

namespace Phenomine\Support\Console\Commands\Migration;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\Database\MigrationRunner;
use Phenomine\Support\File;

class RunMigrationCommand extends Command
{
    protected $name = 'migrate';
    protected $description = 'Run database migration';

    protected $runner;
    protected $currentBatch = 1;

    public function handle()
    {
        if (config('app.env') == 'production') {
            $this->newLine();
            $this->warn(' You are running in production mode. Please be careful!');
            $result = $this->confirm('Do you want to continue?');
            if (!$result) {
                $this->warn('Operation cancelled.');
                return;
            }
        }

        // check if database is exist
        try {
            db()->select('db_migrations', ['migration']);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'no such table') === false) {
                // only support for mysql, mariadb, and postgresql
                $db = config('database.driver');
                if ($db != 'mysql' && $db != 'mariadb' && $db != 'pgsql') {
                    $this->error('Database "' . config('database.database') . '" is not exist. Please create it manually.');
                    return;
                } else {
                    // ask for create database
                    $create = $this->confirm('Database "' . config('database.database') . '" is not exist. Would you like to create it?');
                    if (!$create) {
                        $this->warn('Migration cancelled.');
                        return;
                    }

                    // create database
                    $query = 'CREATE DATABASE ' . config('database.database');
                    if ($db == 'mysql' || $db == 'mariadb') {
                        $query .= ' CHARACTER SET ' . config('database.charset', 'utf8mb4') . ' COLLATE ' . config('database.collation', 'utf8mb4_unicode_ci');
                    }

                    // manually run query
                    $con = config('database.driver');
                    $host = config('database.host');
                    $port = config('database.port');
                    $username = config('database.username');
                    $password = config('database.password');
                    $pdo = new \PDO("$con:host=$host;port=$port", $username, $password);

                    $pdo->exec($query);
                    $pdo = null;

                    $this->info('Database "' . config('database.database') . '" created.');
                }
            }
        }

        $this->runner = new MigrationRunner();

        // get highest batch number
        $this->currentBatch = $this->runner->getCurrentBatchNumber();

        $migrations = $this->runner->getMigrationFiles();
        if (count($migrations) == 0) {
            $this->info('Nothing to migrate');

            return true;
        }

        $exist = db()->select(config('database.migration_table', 'db_migrations'), ['migration']);

        $counter = 0;
        foreach ($migrations as $migration) {
            foreach ($exist as $e) {
                if ($e['migration'] == File::getName($migration['file'])) {
                    continue 2;
                }
            }
            $counter++;
            $this->warn('Migrating :     '.$migration['class']);
            $result = $this->runner->run($migration, $this->currentBatch + 1);
            if ($result['status'] == 'skipped') {
                $this->line('Skipped :       '.$migration['class'].' ('.$result['message'].')');
            } elseif ($result['status'] == 'success') {
                $this->info('Migrated :      '.$migration['class']);
            }
        }

        if ($counter == 0) {
            $this->info('Nothing to migrate');

            return true;
        }

        return true;
    }
}
