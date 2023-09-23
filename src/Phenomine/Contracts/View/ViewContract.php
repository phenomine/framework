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
    protected $basePath;
    protected $latte;

    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->basePath = $basePath;
        } else {
            $this->basePath = base_path('res/views');
        }
        $this->latte = new Engine();
        $this->latte->setLoader(new Loader($this->basePath));
        $this->latte->setTempDirectory(storage_path('framework/views'));
    }
}
