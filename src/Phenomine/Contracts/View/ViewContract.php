<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\View;

use Latte\Engine;

class ViewContract
{
    protected $latte;

    public function __construct()
    {
        $this->latte = new Engine();
        $this->latte->setLoader(new Loader(base_path('res/views')));
        $this->latte->setTempDirectory(storage_path('framework/views'));
    }
}
