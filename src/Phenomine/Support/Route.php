<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

use Phenomine\Contracts\Route\Instance;
use Phenomine\Contracts\Route\Method;
use Phenomine\Contracts\Route\Param;
use Phenomine\Contracts\Route\Path;
use Phenomine\Support\Exceptions\RouteException;

class Route extends Instance {

    private function set($method, $uri, $handler)
    {
        $instance = new Instance();
        $instance->method = $method;
        $instance->uri = $uri;
        $instance->handler = $handler;

        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;

        return $instance;
    }

    private static function split($uri)
    {
        $uri = trim($uri, '/');
        $paths = explode('/', $uri);
        $result = array();
        foreach ($paths as $path) {
            if (!(trim($path) == '' || trim($path) == '\\')) {
                $result[] = $path;
            }
        }
        return $result;
    }

    private static function splitPath($uri, callable $callback)
    {
        $paths = static::split($uri);
        foreach ($paths as $path) {
            $callback($path, $paths);
        }
    }
    private static function countSplitPath($uri)
    {
        $paths = static::split($uri);
        return count($paths);
    }

    private function addRoute($method, $uri, $handler)
    {
        $instance = $this->set($method, $uri, $handler);
        global $_routes;

        static::splitPath($uri, function ($path, $paths) use ($instance) {
            $routePath = new Path();
            $routePath->index = array_search($path, $paths);
            $routePath->name = $path;
            $routePath->static = true;
            $routePath->optional = false;

            if (preg_match('/\{(.*)\}/', $path, $matches)) {
                // check if parameter is exist
                if (in_array($matches[1], array_column($instance->params, 'name'))) {
                    throw new RouteException("Parameter {$matches[1]} already exist");
                }

                $data = new Param();
                $data->name = $matches[1];
                $data->optional = false;
                $data->position = array_search($path, $paths);

                $routePath->static = false;
                // Check if the parameter is optional
                if (preg_match('/\{(.*)\?\}/', $path, $matches)) {
                    $data->optional = true;
                    $routePath->optional = true;
                }
                $instance->params[] = $data;
            }

            $instance->details[] = $routePath;
        });

        $_routes[] = $instance;
        return $this;
    }

    public function name($name)
    {
        // check if name already exist
        global $_routes;
        foreach ($_routes as $route) {
            if ($route->name == $name) {
                throw new RouteException("Route name {$name} already exist");
            }
        }

        if (empty($this->uri)) {
            throw new RouteException("Route does not exist");
        }
        $this->name = $name;

        // change name on global routes
        foreach ($_routes as $key => $route) {
            if ($route->route == $this->uri) {
                $_routes[$key]->name = $name;
            }
        }

        return $this;
    }

    public static function get($route, $handler)
    {
        $instance = new Route();
        return $instance->addRoute(Method::GET, $route, $handler);
    }

    public function post($route, $handler)
    {
        return static::addRoute(Method::POST, $route, $handler);
    }

    public function put($route, $handler)
    {
        return static::addRoute(Method::PUT, $route, $handler);
    }

    public function patch($route, $handler)
    {
        return static::addRoute(Method::PATCH, $route, $handler);
    }

    public function delete($route, $handler)
    {
        return static::addRoute(Method::DELETE, $route, $handler);
    }

    public function middleware($middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getName()
    {
        return $this->name;
    }

    public static function getRoutes()
    {
        global $_routes;
        return $_routes;
    }

    public static function getRouteByName($name)
    {
        global $_routes;
        foreach ($_routes as $route) {
            if ($route->name == $name) {
                return $route;
            }
        }
        return null;
    }

    public static function predictRoute()
    {
        $uri = Request::getUri();
        global $_routes;

        foreach ($_routes as $route) {
            if (static::checkRoute($uri, $route)) {
                return $route;
            }
        }

        return null;
    }

    private static function checkRoute($uri, $route)
    {
        $count = static::countSplitPath($uri);
        $index = -1;
        $valid = true;
        if ($count > 0) {
            foreach (static::split($uri) as $path) {
                $index++;

                if (!isset($route->details[$index])) {
                    $valid = false;
                    break;
                }

                $_route = $route->details[$index];
                if ($_route->static) {
                    if ($path != $_route->name) {
                        $valid = false;
                    }
                } else {
                    if ($_route->optional) {
                        if ($path != $_route->name) {
                            $valid = false;
                        }
                    }
                }
            }
        }

        return $valid;
    }
}
