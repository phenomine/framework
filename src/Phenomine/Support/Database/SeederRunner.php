<?php

namespace Phenomine\Support\Database;

use Phenomine\Support\Application;
use Phenomine\Support\File;
use Phenomine\Support\Str;

class SeederRunner
{
    private $seeders;

    public function run($seeder)
    {
        require_once $seeder['file'];

        $runner = new $seeder['class']();
        $runner->run();

        return [
            'success' => true,
            'status'  => 'success',
            'message' => "Seeder {$seeder['class']} created",
        ];
    }

    public function getSeederFile($file)
    {
        $seeders = $this->getSeederFiles();

        foreach ($seeders as $seeder) {
            if (File::getName($seeder['file']) == $file) {
                return $seeder;
            }
        }
    }

    public static function getSeederFiles()
    {
        $seederFiles = [];
        $files = File::allFiles(base_path('db/seeders'), true);
        foreach ($files as $file) {
            if (!Str::endsWith($file, '.php')) {
                continue;
            }

            $className = Application::getClassName($file);

            if ($className != null) {
                $seederFiles[] = [
                    'file'  => $file,
                    'class' => $className,
                ];
            }
        }

        return $seederFiles;
    }
}
