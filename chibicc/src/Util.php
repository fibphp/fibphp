<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:18
 */

namespace chibicc;

use Tiny\Util as _Util;

class Util extends _Util
{
    public static function strcmp(string $str1, string $str2): int
    {
        return strcmp($str1, $str2);
    }

    public static function strncmp(string $str1, string $str2, int $len): int {
        return strncmp($str1, $str2, $len);
    }
}