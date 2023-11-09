<?php

/*
| The Phenomine Framework
| Copyright (c) 2023 Muhammad Fahli Saputra
| https://github.com/phenomine/framework
|
*/

namespace Phenomine\Support;

class Str
{
    public static function splitDot($string)
    {
        return explode('.', $string);
    }

    public static function length($string)
    {
        return strlen($string);
    }

    public static function contains($string, $needle)
    {
        return strpos($string, $needle) !== false;
    }

    public static function startsWith($string, $needle)
    {
        return strpos($string, $needle) === 0;
    }

    public static function endsWith($string, $needle)
    {
        return substr($string, -strlen($needle)) === $needle;
    }

    public static function replace($string, $search, $replace)
    {
        return str_replace($search, $replace, $string);
    }

    public static function replaceFirst($string, $search, $replace)
    {
        return preg_replace('/'.$search.'/', $replace, $string, 1);
    }

    public static function replaceLast($string, $search, $replace)
    {
        $pos = strrpos($string, $search);
        if ($pos !== false) {
            $string = substr_replace($string, $replace, $pos, strlen($search));
        }
        return $string;
    }

    public static function split($string, $delimiter = '.')
    {
        if (is_array($delimiter)) {
            $regex = '/[';
            foreach ($delimiter as $key => $value) {
                if ($value == '\\') {
                    $regex .= "\\\\\\\\";
                } else if ($value == '/') {
                    $regex .= '\\/';
                } else {
                    $regex .= $value;
                }
            }
            $regex .= ']/';
            return preg_split($regex, $string);
        } else {
            return explode($delimiter, $string);
        }
    }

    public static function trim($string, $character_mask = " \t\n\r\0\x0B")
    {
        return trim($string, $character_mask);
    }

    public static function trimLeft($string, $character_mask = " \t\n\r\0\x0B")
    {
        return ltrim($string, $character_mask);
    }

    public static function trimRight($string, $character_mask = " \t\n\r\0\x0B")
    {
        return rtrim($string, $character_mask);
    }

    public static function toLower($string)
    {
        return strtolower($string);
    }

    public static function toUpper($string)
    {
        return strtoupper($string);
    }

    public static function toTitle($string)
    {
        return ucwords($string);
    }

    public static function toCamel($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    public static function toSnake($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public static function toKebab($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $string));
    }

    public static function toSlug($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public static function random($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = Str::length($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function randomNumeric($length = 16)
    {
        $characters = '0123456789';
        $charactersLength = Str::length($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function randomAlpha($length = 16)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = Str::length($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function randomAlphaNumeric($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = Str::length($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function randomUuidV4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(16384, 20479),
            random_int(32768, 49151),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535)
        );
    }

    public static function removeLast($string, $needle)
    {
        return preg_replace('/'.$needle.'$/', '', $string);
    }


}
