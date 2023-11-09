<?php

namespace Phenomine\Support;

use Phenomine\Contracts\Application\ApplicationContract;

class Application extends ApplicationContract
{
    public function run()
    {
        if ($this->console != null) {
            global $_app;
            $_app = $this;
            $this->console->run();
        } else {
            global $_app;
            $this->request = new Request($this);
            $_app = $this;
            $this->request->handle();
        }
    }

    public function init()
    {
        $this->loadRoutes();
        $this->route = Route::predictRoute();
    }

    public static function getNamespace($file)
    {
        $file = file_get_contents($file);
        $pattern = '/namespace (.*)\;/';
        $classPattern = '/class (.*) /';
        preg_match($pattern, $file, $matches);
        preg_match($classPattern, $file, $classMatches);
        $namespace = null;

        $classPattern = trim($classMatches[1], '/');
        $classPattern = explode(' ', $classPattern);
        $classPattern = trim($classPattern[0]);
        if (!empty($matches)) {
            $namespace = $matches[1].'\\'.$classPattern;
        }

        return $namespace;
    }

    /**
     * Call a callable
     *
     * @param array $callable
     * @param array $parameters
     */
    public function call($callable, $parameters = [])
    {
        $class = $callable[0];
        $method = $callable[1];

        if (is_null($class)) {
            $class = new $class;
        }

        $class->{$method}(...$parameters);
    }

    /**
     * Call a callable
     *
     * @param array $callable
     * @param array $parameters
     */
    public function directCall($callable, $parameters = [])
    {
        $class = $callable[0];
        $method = $callable[1];

        $class->{$method}(...$parameters);
    }

    public function make($class, $parameters = [])
    {
        return new $class(...$parameters);
    }
}
