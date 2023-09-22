<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

class Str {
    public static function splitDot($string) {
        return explode('.', $string);
    }
}
