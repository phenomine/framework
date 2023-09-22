<?php

namespace Phenomine\Support;

use Phenomine\Contracts\Application\ApplicationContract;

class Application extends ApplicationContract {

    public function run() {
        if ($this->console) {
            $this->console->run();
        }
    }

    public function init() {
        echo json_encode(Route::predictRoute());
    }

}
