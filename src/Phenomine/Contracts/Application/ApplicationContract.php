<?php

namespace Phenomine\Contracts\Application;

use Phenomine\Support\Console\Console;
use Symfony\Component\Console\Application;

class ApplicationContract {
    protected $version;
    protected Application $console;
    protected $routes;
    public $route;
    public $request;

    public function console() {
        $this->console = new Application();
        $consoleNamespace = Console::getAllConsoleNamespace();
        foreach ($consoleNamespace as $console) {
            $this->console->add(new $console);
        }
        return $this;
    }

    public function loadRoutes() {
        foreach (glob(base_path() . '/routes/*.php') as $file) {
            require_once $file;
        }
    }
}
