<?php

namespace Phenomine\Support;

use Exception;
use Phenomine\Contracts\Application\ApplicationContract;
use Phenomine\Support\Exceptions\ExceptionHandler;

class Application extends ApplicationContract
{
    public function run()
    {
        if ($this->console != null) {
            global $_app;
            $_app = $this;
            $this->console->run();
        } else {

            ob_start(); // start output buffering

            global $_app;
            $this->request = new Request($this);
            $_app = $this;
            $this->request->handle();
        }
    }

    public function prepare() {
        $handler = new ExceptionHandler();

        set_error_handler([$handler, 'errorHandler']);
        set_exception_handler([$handler, 'exceptionHandler']);
    }

    public function init()
    {
        $this->prepare();
        new Env(); // load .env file
        $env = env('APP_ENV', "");
        if ($env == "") {
            throw new \Exception('Application environment not set correctly. Please check your configuration before running application.');
        }

        if ($env == "production") {
            error_reporting(0);
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('log_errors', 1);
        }

        $this->loadRoutes();
        $this->route = Route::predictRoute();
    }

    public function initConsole()
    {
        new Env(); // load .env file
        $this->loadRoutes();
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

    public static function getClassName($file)
    {
        $file = file_get_contents($file);
        $classPattern = '/class (.*) /';
        preg_match($classPattern, $file, $matches);
        $class = null;

        $classPattern = trim($matches[1], '/');
        $classPattern = explode(' ', $classPattern);
        $classPattern = trim($classPattern[0]);
        if (!empty($matches)) {
            $class = $classPattern;
        }

        return $class;
    }

    /**
     * Call a callable.
     *
     * @param array $callable
     * @param array $parameters
     */
    public function call($callable, $parameters = [])
    {
        $class = $callable[0];
        $method = $callable[1];

        if (is_null($class)) {
            $class = new $class();
        }

        $class->{$method}(...$parameters);
    }

    /**
     * Call a callable.
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
