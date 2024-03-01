<?php

namespace Phenomine\Support\Console\Commands\Model;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;

class MakeModelCommand extends Command
{
    protected $name = 'make:model';
    protected $description = 'Create a new model file';
    protected $arguments = [
        'table' => 'Table name',
    ];

    protected $options = [
        'm' => 'Make a migration for the table',
    ];

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

        // make model
        $tableName = ucfirst(strtolower($this->argument('table')));
        // check if last character is 's'
        if (substr($tableName, -1) == 's') {
            $tableName = substr($tableName, 0, -1);
        }
        $fileModel = File::createFileFromString(base_path('app/Models'), $tableName, '.php');
        $stubModel = File::readAndReplace(__DIR__.'../../../../../Stubs/model.stub', [
            'class' => $tableName,
        ]);
        File::write($fileModel['file'], $stubModel);

        // check if option migration is set
        if ($this->option('m')) {
            $name = 'migration_'.date('YmdHis').'_'.$this->argument('table');
            $file = File::createFileFromString(base_path('db/migrations'), $name, '.php');

            $stub = File::readAndReplace(__DIR__.'../../../../../Stubs/migration.stub', [
                'migration' => $name,
                'table'     => $this->argument('table'),
            ]);

            File::write($file['file'], $stub);
        }

        $this->info('Model created successfully');

        return true;
    }
}
