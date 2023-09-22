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
            $this->request = new Request($this);
            $this->request->handle();
        }
    }

    public function init()
    {
        $this->loadRoutes();
        $this->route = Route::predictRoute();
    }
}
