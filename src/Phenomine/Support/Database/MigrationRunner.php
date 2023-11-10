<?php

namespace Phenomine\Support\Database;

use Phenomine\Support\Application;
use Phenomine\Support\File;
use Phenomine\Support\Str;

class MigrationRunner
{
    private $migrations;

    public function __construct()
    {
        $this->getMigrationFiles();

        if (!db()->tableExist(config('database.migration_table', 'db_migrations'))) {
            $this->createMigrationTable();
        }

        $this->migrations = db()->select(config('database.migration_table', 'db_migrations'), ['migration', 'batch']);
    }

    public function createMigrationTable()
    {
        $create = db()->create(config('database.migration_table', 'db_migrations'), [
            'id' => [
                'INT',
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY',
            ],
            'migration' => [
                'VARCHAR(200)',
                'NOT NULL',
            ],
            'batch' => [
                'INT',
                'NOT NULL',
            ],
        ]);
    }

    public function getCurrentBatchNumber()
    {
        $batch = 0;
        foreach ($this->migrations as $migration) {
            if ($migration['batch'] > $batch) {
                $batch = $migration['batch'];
            }
        }

        return $batch;
    }

    public function addMigration($migration, $batch)
    {
        db()->insert(config('database.migration_table', 'db_migrations'), [
            'migration' => File::getName($migration),
            'batch'     => $batch,
        ]);
    }

    public function run($migration, $batch)
    {
        require_once $migration['file'];

        $exist = db()->select(config('database.migration_table', 'db_migrations'), ['migration'], [
            'migration' => File::getName($migration['file']),
        ]);

        if (count($exist) > 0) {
            return [
                'success' => false,
                'status'  => 'skipped',
                'message' => 'Migration has already been run',
            ];
        }

        $runner = new $migration['class']();
        $runner->up();

        if (db()->tableExist($runner->getTable())) {
            return [
                'success' => false,
                'status'  => 'skipped',
                'message' => "Table {$runner->getTable()} already exists",
            ];
        }

        $query = $runner->getColumns();

        db()->create($runner->getTable(), $query);

        $this->addMigration($migration['file'], $batch);

        return [
            'success' => true,
            'status'  => 'success',
            'message' => "Table {$runner->getTable()} created",
        ];
    }

    public function rollback()
    {
        $batch = $this->getCurrentBatchNumber();

        $migrations = db()->select(config('database.migration_table', 'db_migrations'), ['migration'], [
            'batch' => $batch,
        ]);

        $counter = 0;
        $migrationRolledBack = [];

        foreach ($migrations as $migration) {
            $migrationFile = $this->getMigrationFile($migration['migration']);
            require_once $migrationFile['file'];

            $runner = new $migrationFile['class']();
            $runner->down();
            db()->delete(config('database.migration_table', 'db_migrations'), [
                'migration' => $migration['migration'],
            ]);
            $counter++;
            $migrationRolledBack[] = $migration['migration'];
        }

        return [
            'success'    => true,
            'status'     => 'success',
            'message'    => 'Rollback success',
            'rollback'   => $counter,
            'migrations' => $migrationRolledBack,
        ];
    }

    public function getMigrationFile($file)
    {
        $migrations = $this->getMigrationFiles();

        foreach ($migrations as $migration) {
            if (File::getName($migration['file']) == $file) {
                return $migration;
            }
        }
    }

    public static function getMigrationFiles()
    {
        $migrationFiles = [];
        $files = File::allFiles(base_path('db/migrations'), true);
        foreach ($files as $file) {
            if (!Str::endsWith($file, '.php')) {
                continue;
            }

            $className = Application::getClassName($file);

            if ($className != null) {
                $migrationFiles[] = [
                    'file'  => $file,
                    'class' => $className,
                ];
            }
        }

        return $migrationFiles;
    }
}
