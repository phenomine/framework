<?php

namespace Phenomine\Support\Console\Commands\Controller;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;
use Phenomine\Support\Route;
use Phenomine\Support\Str;

class MakeControllerCommand extends Command {
    protected $name = 'make:controller';
    protected $description = 'Create a new controller';
    protected $arguments = [
        'name' => 'Controller name'
    ];

    public function handle() {
        $file = File::createFileFromString(base_path('app/Controllers'), $this->argument('name'), '.php');
        if (!$file) {
            $this->error('Controller already exists');
            return false;
        }

        $stub = File::readAndReplace(__DIR__.'../../../../../Stubs/controller.stub', [
            'namespace' => 'App\\Controllers' . ($file['directory'] ? '\\' . Str::replace($file['directory'], '/', '\\') : ''),
            'class' => Str::replace(File::getName($file['file']), '.php', '')
        ]);

        File::write($file['file'], $stub);

        $this->info('Controller created successfully');
        return true;
    }
}
