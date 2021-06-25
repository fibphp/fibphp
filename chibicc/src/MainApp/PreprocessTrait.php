<?php


namespace chibicc\MainApp;


trait PreprocessTrait
{
    public array $options = [];

    public static function define_macro(string $k, string $v): void
    {

    }

    public static function undef_macro(string $k): void
    {

    }

    public static function define(string $str): void
    {
        $idx = strpos($str, '=');
        if ($idx !== false) {
            $k = substr($str, 0, $idx);
            $v = substr($str, $idx + 1);
            self::define_macro($k, $v);
        } else {
            self::define_macro($str, "1");
        }
    }

}