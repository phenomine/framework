<?php

namespace Phenomine\Support;

class File
{
    public static function exists($path) {
        return file_exists($path);
    }
}
