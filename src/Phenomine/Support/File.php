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
    public static function createFileFromString($origin, $string, $extension = '.php')
    {
        // check if last $string is .php
        if (Str::endsWith($string, '.php')) {
            $string = Str::removeLast($string, '.php');
        }

        $keys = Str::split($string, ['.', '/', '\\']);

        $file_dir = $origin;
        $filename = $string;
        $directory = null;
        // if $keys has more than 1 element, expect the last element as filename and the rest as directory
        // create directory if not exists
        if (count($keys) > 1) {
            $filename = $keys[count($keys) - 1];
            $directory = '';
            for ($i = 0; $i < count($keys) - 1; $i++) {
                $directory .= $keys[$i].'/';
            }
            $directory = rtrim($directory, '/');
            if (!static::exists($origin.'/'.$directory)) {
                mkdir($origin.'/'.$directory, 0777, true);
            }
            $file_dir = $origin.'/'.$directory;
        }

        $file = $file_dir.'/'.$filename.$extension;
        if (!static::exists($file)) {
            touch($file);
            return [
                'file' => $file,
                'directory' => $directory
            ];
        } else {
            return false;
        }
    }

    public static function getName($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    public static function getExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function read($path)
    {
        return file_get_contents($path);
    }

    public static function readAndReplace($path, $params)
    {
        $content = file_get_contents($path);
        foreach ($params as $key => $value) {
            $content = str_replace('{{'.$key.'}}', $value, $content);
        }
        return $content;
    }

    public static function write($path, $content)
    {
        file_put_contents($path, $content);
    }
}
