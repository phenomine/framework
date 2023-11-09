<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

use Phenomine\Support\File;
use Phenomine\Support\Request;
use Phenomine\Support\Str;
use Phenomine\Support\View;

if (!function_exists('app')) {
    /**
     * Get the app instance.
     *
     * @return \Phenomine\Support\Application
     */
    function app()
    {
        global $_app;

        return $_app;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the base path of the Phenomine installation.
     *
     * @param string $path
     *
     * @return string
     */
    function base_path($path = '')
    {
        global $basePath;
        if ($basePath) {
            return $basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
        }

        return __DIR__.'/../../../../..'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the app path of the Phenomine installation.
     *
     * @param string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return base_path('app').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the config path of the Phenomine installation.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return base_path('config').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the public path of the Phenomine installation.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return base_path('public').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the storage path of the Phenomine installation.
     *
     * @param string $path
     *
     * @return string
     */
    function storage_path($path = '')
    {
        return base_path('storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('config')) {
    /**
     * Get the config key-value.
     *
     * @param string $key
     *
     * @return string
     */
    function config(string $key, $default = null)
    {
        $keys = Str::splitDot($key);
        $config = null;
        $index = -1;
        foreach ($keys as $key) {
            $index++;
            if (File::exists(config_path($key.'.php'))) {
                $config = require config_path($key.'.php');
                break;
            }
        }

        $keys = array_slice($keys, $index + 1);
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                $config = $default;
                break;
            }
        }

        return $config;
    }
}

if (!function_exists('view')) {
    /**
     * Get the view.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    function view($view, $data = [])
    {
        $instance = new View();

        return $instance->render($view, $data);
    }
}

if (!function_exists('abort')) {
    /**
     * Abort the application.
     *
     * @param int    $code
     * @param string $message
     *
     * @return void
     */
    function abort($code)
    {
        Request::abort($code);
    }
}

if (!function_exists('request')) {
    /**
     * Get the request instance.
     *
     * @return \Phenomine\Support\Request|string|array|null
     */
    function request($key = null, $default = null)
    {
        if ($key) {
            return Request::get($key, $default);
        } else {
            $request = new Request(app());

            return $request;
        }
    }
}

if (!function_exists('db')) {
    /**
     * Get the database instance.
     *
     * @return \Phenomine\Support\DB
     */
    function db()
    {
        return app()->make(\Phenomine\Support\DB::class);
    }
}

if (!function_exists('route')) {
    /**
     * Get the route instance.
     *
     * @return \Phenomine\Support\Route
     */
    function route($name, $params = [])
    {
        return app()->make(\Phenomine\Support\Route::class)->buildRouteWithParams($name, $params);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to an url.
     */
    function redirect($uri)
    {
        return header('Location: '.$uri);
    }
}
