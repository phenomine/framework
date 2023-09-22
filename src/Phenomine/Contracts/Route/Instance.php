<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Contracts\Route;

class Instance
{
    /**
     * The HTTP method.
     */
    public string $method;

    /**
     * The route path.
     */
    public string $uri;

    /**
     * The route handler.
     */
    public $handler;

    /**
     * The route parameters.
     */
    public array $params = [];

    /**
     * The route uri details, split by slash.
     */
    public array $details = [];

    /**
     * The route middlewares.
     */
    public array $middlewares = [];

    /**
     * The route name.
     */
    public $name;
}
