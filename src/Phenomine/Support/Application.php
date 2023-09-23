<?php

namespace Phenomine\Support;

use Phenomine\Contracts\Application\ApplicationContract;

class Application extends ApplicationContract
{
    public function run()
    {
        if ($this->console != null) {
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
        preg_match($pattern, $file, $matches);
        $namespace = null;
        if (!empty($matches)) {
            $namespace = $matches[1];
        }

        return $namespace;
    }
}
