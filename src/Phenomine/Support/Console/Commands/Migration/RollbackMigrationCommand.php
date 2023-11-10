<?php

namespace Phenomine\Support\Console\Commands\Migration;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\Database\MigrationRunner;

class RollbackMigrationCommand extends Command
{
    protected $name = 'migrate:rollback';
    protected $description = 'Rollback 1 batch of database migration';

    protected $runner;

    public function handle()
    {
        $this->runner = new MigrationRunner();

        $this->info('Running rollback...');
        $data = $this->runner->rollback();

        $this->info('Rollback completed');
        $this->info('Current batch number : '.$this->runner->getCurrentBatchNumber());
        $this->info('Last batch number    : '.($this->runner->getCurrentBatchNumber() - 1));
        $this->info('Total rollback       : '.$data['rollback']);

        return true;
    }
}
