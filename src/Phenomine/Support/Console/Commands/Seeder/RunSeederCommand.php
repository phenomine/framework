<?php

namespace Phenomine\Support\Console\Commands\Seeder;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;
use Phenomine\Support\Database\SeederRunner;

class RunSeederCommand extends Command
{
    protected $name = 'db:seed';
    protected $description = 'Run database seeder';

    protected $runner;

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


        $this->runner = new SeederRunner();

        $seeders = $this->runner->getSeederFiles();
        if (count($seeders) == 0) {
            $this->info('Nothing to seed');
            return true;
        }
        $counter = 0;
        foreach ($seeders as $seed) {
            $counter++;
            $this->warn('Running seeder :        '.$seed['class']);
            $result = $this->runner->run($seed);
            if ($result['status'] == 'skipped') {
                $this->line('Skipped :               '.$seed['class'].' ('.$result['message'].')');
            } elseif ($result['status'] == 'success') {
                $this->info('Seeding complete :      '.$seed['class']);
            }
        }

        if ($counter == 0) {
            $this->info('Nothing to seed');

            return true;
        }

        return true;
    }
}
