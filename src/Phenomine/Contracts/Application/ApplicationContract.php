<?php

namespace Phenomine\Contracts\Application;

use Phenomine\Support\Console\Console;
use Symfony\Component\Console\Application;

class ApplicationContract
{
    /**
     * The Phenomine framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';
    
    protected $version;
    protected $console;
    public $routes;
    public $route;
    public $request;

    public function console()
    {
        $this->console = new Application();
        $consoleNamespace = Console::getAllConsoleNamespace();
        foreach ($consoleNamespace as $console) {
            $this->console->add(new $console());
        }

        return $this;
    }

    public function loadRoutes()
    {
        foreach (glob(base_path().'/routes/*.php') as $file) {
            require_once $file;
        }
    }

    public static function version()
    {
        return static::VERSION;
    }
}
