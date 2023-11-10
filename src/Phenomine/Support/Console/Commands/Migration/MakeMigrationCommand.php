<?php

namespace Phenomine\Support\Console\Commands\Migration;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;
use Phenomine\Support\Route;
use Phenomine\Support\Str;

class MakeMigrationCommand extends Command {
    protected $name = 'make:migration';
    protected $description = 'Create a new migration file';
    protected $arguments = [
        'table' => 'Table name'
    ];

    public function handle() {

        $name = 'migration_' . date('YmdHis') . '_' . $this->argument('table');
        $file = File::createFileFromString(base_path('db/migrations'), $name, '.php');

        $stub = File::readAndReplace(__DIR__.'../../../../../Stubs/migration.stub', [
            'migration' => $name,
            'table' => $this->argument('table')
        ]);

        File::write($file['file'], $stub);

        $this->info('Migration created successfully');
        return true;
    }
}
