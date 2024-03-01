<?php

namespace Phenomine\Support\Console\Commands\Seeder;

use Phenomine\Contracts\Command\Command;
use Phenomine\Support\File;
use Phenomine\Support\Str;

class MakeSeederCommand extends Command
{
    protected $name = 'make:seeder';
    protected $description = 'Create a new seeder file';
    protected $arguments = [
        'name' => 'Seeder name',
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

        $name = ucfirst(Str::toCamel($this->argument('name')));
        $file = File::createFileFromString(base_path('db/seeders'), $name, '.php');

        $stub = File::readAndReplace(__DIR__.'../../../../../Stubs/seeder.stub', [
            'class' => $name,
        ]);

        File::write($file['file'], $stub);

        $this->info('Seeder created successfully. To run the seeder, use the command `php phenomine db:seed`.');

        return true;
    }
}
