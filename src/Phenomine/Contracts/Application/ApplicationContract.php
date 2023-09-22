<?php

namespace Phenomine\Contracts\Application;

use Symfony\Component\Console\Application;

class ApplicationContract {
    protected $version;
    protected Application $console;

    public function console() {
        if (!$this->console) {
            $this->console = new Application();
        }
        return $this;
    }
}
