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
}
