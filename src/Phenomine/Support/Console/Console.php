<?php

namespace Phenomine\Support\Console;

use Phenomine\Support\Application;
use Phenomine\Support\File;
use Phenomine\Support\Str;

class Console
{
    public static function getAllConsoleNamespace()
    {
        $namespaces = [];
        $files = File::allFiles(__DIR__.'/Commands/', true);
        foreach ($files as $file) {
            if (!Str::endsWith($file, 'Command.php')) {
                continue;
            }
            $namespace = Application::getNamespace($file);
            if ($namespace != null) {
                $namespaces[] = $namespace;
            }
        }

        return $namespaces;
    }
}
