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

class Route extends Instance
{
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

    public static function split($uri)
    {
        $uri = trim($uri, '/');
        $paths = explode('/', $uri);
        $result = [];
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
            throw new RouteException('Route does not exist');
        }
        $this->name = $name;

        // change name on global routes
        foreach ($_routes as $key => $route) {
            if ($route->uri == $this->uri) {
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

    public static function post($route, $handler)
    {
        $instance = new Route();

        return $instance->addRoute(Method::POST, $route, $handler);
    }

    public static function put($route, $handler)
    {
        $instance = new Route();

        return $instance->addRoute(Method::PUT, $route, $handler);
    }

    public static function patch($route, $handler)
    {
        $instance = new Route();

        return $instance->addRoute(Method::PATCH, $route, $handler);
    }

    public static function delete($route, $handler)
    {
        $instance = new Route();

        return $instance->addRoute(Method::DELETE, $route, $handler);
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
        $method = Request::method();
        global $_routes;

        foreach ($_routes as $route) {
            if (static::checkRoute($uri, $route, $method)) {
                return $route;
            }
        }

        return null;
    }

    private static function checkRoute($uri, $route, $method)
    {
        $count = static::countSplitPath($uri);
        $_uri = static::split($uri);
        $index = -1;
        $valid = true;

        if ($route->method != $method) {
            return false;
        }

        if (count($route->details) < $count) {
            return false;
        }

        foreach ($route->details as $path) {
            $index++;
            if (!$path->static) {
                if (!$path->optional) {
                    if (!isset($_uri[$index])) {
                        $valid = false;
                    }
                }
            } else {
                if (isset($_uri[$index])) {
                    if ($path->name != $_uri[$index]) {
                        $valid = false;
                    }
                } else {
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    public function buildRouteWithParams($route_name, $params)
    {
        $route = static::getRouteByName($route_name);
        if ($route == null) {
            throw new RouteException("Route {$route_name} does not exist");
        }
        $uri = '';
        if (!empty($params)) {
            $index = -1;
            foreach ($route->details as $path) {
                $index++;
                if ($path->static) {
                    $uri .= $path->name;
                } else {
                    $param_name = str_replace(['{', '}', '?'], '', $path->name);
                    if (isset($params[$param_name])) {
                        $uri .= $params[$param_name];
                    } else {
                        if (!$path->optional) {
                            throw new RouteException("Parameter {$param_name} is required");
                        }
                    }
                }
                if ($index < count($route->details) - 1) {
                    $uri .= '/';
                }
            }
        } else {
            $uri = $route->uri;
        }

        return $uri;
    }
}
