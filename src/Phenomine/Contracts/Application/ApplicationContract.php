<?php

namespace Phenomine\Contracts\Application;

use Phenomine\Support\Console\Console;
use Symfony\Component\Console\Application;

class ApplicationContract {
    protected $version;
    protected Application $console;

    public function console() {
        if (!$this->console) {
            $this->console = new Application();
            $consoleNamespace = Console::getAllConsoleNamespace();
            foreach ($consoleNamespace as $console) {
                $this->console->add(new $console);
            }
        }
        return $this;
    }
}
