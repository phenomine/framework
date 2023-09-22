<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\Route;

class Path
{
    /**
     * The path index on the route.
     */
    public int $index;

    /**
     * The path name.
     */
    public string $name;

    /**
     * The path static.
     */
    public bool $static;

    /**
     * The path optional.
     */
    public bool $optional;

    /**
     * Create a new path instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->index = 0;
        $this->name = '';
        $this->static = false;
        $this->optional = false;
    }
}
