<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\Route;

class Param
{
    /**
     * The parameter name.
     */
    public string $name;

    /**
     * The parameter type.
     */
    public bool $optional;

    /**
     * The parameter position index.
     */
    public int $position;
}
