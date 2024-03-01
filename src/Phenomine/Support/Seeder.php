<?php

namespace Phenomine\Support;

class Seeder
{
    public function run()
    {
        //
    }

    public function call($seeder)
    {
        if (is_array($seeder)) {
            foreach ($seeder as $seed) {
                $this->call($seed);
            }
        } else {
            $seeder = new $seeder();
            $seeder->run();
        }
    }
}
