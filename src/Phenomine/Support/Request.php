<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

class Request
{
    protected Application $app;

    public static function get($param, $default = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                return $param ? (isset($_GET[$param]) ? $_GET[$param] : '') : $_GET;
                break;
            case 'POST':
                return $param ? (isset($_POST[$param]) ? $_POST[$param] : '') : $_POST;
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);

                return $param ? (isset($_PUT[$param]) ? $_PUT[$param] : '') : $_PUT;
                break;
            case 'PATCH':
                parse_str(file_get_contents('php://input'), $_PATCH);

                return $param ? (isset($_PATCH[$param]) ? $_PATCH[$param] : '') : $_PATCH;
                break;
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $_DELETE);

                return $param ? (isset($_DELETE[$param]) ? $_DELETE[$param] : '') : $_DELETE;
                break;
            default:
                return $default;
                break;
        }
    }

    public static function all()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                return $_GET;
                break;
            case 'POST':
                return $_POST;
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);

                return $_PUT;
                break;
            case 'PATCH':
                parse_str(file_get_contents('php://input'), $_PATCH);

                return $_PATCH;
                break;
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $_DELETE);

                return $_DELETE;
                break;
            default:
                return [];
                break;
        }
    }

    public static function has($param)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                return isset($_GET[$param]);
                break;
            case 'POST':
                return isset($_POST[$param]);
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);

                return isset($_PUT[$param]);
                break;
            case 'PATCH':
                parse_str(file_get_contents('php://input'), $_PATCH);

                return isset($_PATCH[$param]);
                break;
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $_DELETE);

                return isset($_DELETE[$param]);
                break;
            default:
                return false;
                break;
        }
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public static function isPjax()
    {
        return isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true';
    }

    public static function isHttps()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
    }

    public static function isSecure()
    {
        return self::isHttps();
    }

    public static function isGet()
    {
        return self::method() == 'GET';
    }

    public static function isPost()
    {
        return self::method() == 'POST';
    }

    public static function isPut()
    {
        return self::method() == 'PUT';
    }

    public static function isPatch()
    {
        return self::method() == 'PATCH';
    }

    public static function isDelete()
    {
        return self::method() == 'DELETE';
    }

    public static function getUri()
    {
        $uri = static::uri();
        $uri = explode('?', $uri);
        $uri = $uri[0];
        $uri = '/'.trim($uri, '/');

        $root = base_path();
        $root = str_replace('\Phenomine\Support', '', $root);
        $root = str_replace('/Phenomine/Support', '', $root);

        // check if the uri contains the root folder name
        if (strpos($uri, basename($root)) !== false) {
            $uri = str_replace(basename($root), '', $uri);
        }

        // check if uri contains two or more slashes on the first character
        if (substr($uri, 0, 2) == '//') {
            $uri = substr($uri, 1);
        }

        return $uri;
    }

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle()
    {
        $route = $this->app->route;

        if (!$route) {
            return static::abort(404);
        }

        $handler = $route->handler;

        $params = $this->params();
        if (is_array($handler)) {
            $controller = new $handler[0]();
            $controller->{$handler[1]}(...$params);
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } else {
            // if the route is a controller
            $handler = explode('@', $handler);
            $controllerName = '\\App\\Controllers\\'.$handler[0];
            $controller = new $controllerName(...$params);
            $controller->{$handler[1]}();
        }
    }

    public function params($key = '')
    {
        $uri = static::getUri();
        $_uri = Route::split($uri);
        $route = $this->app->route;
        $params = $route->params;

        if ($key) {
            foreach ($params as $param) {
                if ($param->name == $key) {
                    $position = $param->position;
                    if (isset($_uri[$position])) {
                        return $_uri[$position];
                    } else {
                        return null;
                    }
                }
            }
        }

        $extract = [];
        foreach ($params as $param) {
            $position = $param->position;
            if (isset($uri[$position])) {
                $extract[] = $_uri[$position];
            }
        }

        return $extract;
    }


    public static function abort($code)
    {
        http_response_code($code);
        $view = new View(__DIR__.'/../views');
        $view->render('errors.'.$code);
        exit;
    }
}
