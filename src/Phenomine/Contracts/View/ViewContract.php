<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\View;

use Latte\Engine;
use Latte\Loaders\FileLoader;

class ViewContract
{
    protected $latte;

    public function __construct()
    {
        $this->latte = new Engine();
        $this->latte->setLoader(new FileLoader(base_path('res/views')));
    }
}
