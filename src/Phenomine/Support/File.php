<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

class File
{
    public static function exists($path)
    {
        return file_exists($path);
    }

    public static function allFiles($path, $recursive = false)
    {
        $files = [];
        $dir = scandir($path);
        foreach ($dir as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path.'/'.$file)) {
                    if ($recursive) {
                        $files = array_merge($files, self::allFiles($path.'/'.$file, $recursive));
                    }
                } else {
                    $files[] = $path.'/'.$file;
                }
            }
        }

        return $files;
    }

    public static function isFile($path)
    {
        return is_file($path);
    }

    public static function isDirectory($path)
    {
        return is_dir($path);
    }

    public static function findFilesFromString($origin, $string, $extension = '.php')
    {
        $keys = Str::splitDot($string);
        $file = null;
        $index = -1;

        // trim origin /
        $origin = rtrim($origin, '/');

        foreach ($keys as $key) {
            $index++;
            if (static::isDirectory($origin.'/'.$key)) {
                $origin = $origin.'/'.$key;
            } else {
                if (static::exists($origin.'/'.$key.$extension)) {
                    $file = $origin.'/'.$key.$extension;
                }
            }
        }

        return $file;
    }

    public static function getFileName($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function getExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}
