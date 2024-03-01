<?php

/*
| The Phenomine Framework
| Copyright (c) 2024 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

class Env {

    public function __construct()
    {
        $this->load();
    }

    public function load() {
        global $_ENV;
        $env = file_get_contents(base_path('.env'));
        $env = explode("\n", $env);
        foreach ($env as $line) {
            // ignore comments
            if (strpos($line, '#') === 0) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line);
                $key = trim($key);
                $value = trim($value);
                $value = str_replace('"', '', $value);
                $value = str_replace("'", '', $value);
                // remove comments
                $value = explode('#', $value);
                $value = $value[0];
                $value = trim($value);
                $_ENV[$key] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        global $_ENV;
        return $_ENV[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        global $_ENV;
        $_ENV[$key] = $value;
    }

    public static function has($key)
    {
        global $_ENV;
        return isset($_ENV[$key]);
    }
}
