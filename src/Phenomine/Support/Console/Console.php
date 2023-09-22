<?php

namespace Phenomine\Support\Console;

class Console {
    public static function getAllConsoleNamespace() {
        $consoleNamespace = [];
        $consolePath = __DIR__ . '/Commands/';
        $consoleFiles = scandir($consolePath);
        foreach ($consoleFiles as $consoleFile) {
            if ($consoleFile != '.' && $consoleFile != '..') {
                $consoleNamespace[] = 'Phenomine\\Support\\Console\\Commands\\' . str_replace('.php', '', $consoleFile);
            }
        }
        return $consoleNamespace;
    }
}
