<?php

namespace Phenomine\Contracts\Application;

use Phenomine\Contracts\Command\Command as PhenomineCommand;
use Phenomine\Support\Console\Console;
use Symfony\Component\Console\Application;

class ApplicationContract
{
    /**
     * The Phenomine framework version.
     *
     * @var string
     */
    const VERSION = '2.0.0';

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
            // check if namespace is symfony or phenomine
            $console = new $console();

            // determine if console is a symfony console or phenomine console
            if ($console instanceof PhenomineCommand) {
                $console = $console->getSymfonyCommandInstance();
            }

            // get instance of symfony command

            $this->console->add($console);
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
