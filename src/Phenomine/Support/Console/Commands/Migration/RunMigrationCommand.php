<?php

namespace Phenomine\Support\Console\Commands\Migration;

use Phenomine\Support\Database\MigrationRunner;
use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;

class RunMigrationCommand extends Command {
    protected $name = 'migrate';
    protected $description = 'Run database migration';

    protected $runner;
    protected $currentBatch = 1;
    public function handle() {
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
            $this->warn('Migrating :     ' . $migration['class']);
            $result = $this->runner->run($migration, $this->currentBatch + 1);
            if ($result['status'] == 'skipped') {
                $this->line('Skipped :       ' . $migration['class'] . ' (' . $result['message'] . ')');
            } else if ($result['status'] == 'success') {
                $this->info('Migrated :      ' . $migration['class']);
            }
        }

        if ($counter == 0) {
            $this->info('Nothing to migrate');
            return true;
        }

        return true;
    }
}
