<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

use Phenomine\Support\File;
use Phenomine\Support\Str;

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
