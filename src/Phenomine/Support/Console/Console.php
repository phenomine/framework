<?php

namespace Phenomine\Support\Console;

use Phenomine\Support\Application;
use Phenomine\Support\File;

class Console
{
    public static function getAllConsoleNamespace()
    {
        $namespaces = [];
        $files = File::allFiles(__DIR__ . '/Commands/', true);
        foreach ($files as $file) {
            $namespace = Application::getNamespace($file);
            if ($namespace != null) {
                $namespaces[] = $namespace;
            }
        }
        return $namespaces;
    }
}
