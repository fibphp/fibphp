<?php

class A
{
    public function normalm($x)
    {
        return $x;
    }

    public static function staticm($x, $y)
    {
        return [$x, $y];
    }

    public static function classm($y)
    {
        return [static::class, $y];
    }
}


$a = new A();
$a->normalm(10);
$a->staticm(10, "hi");
$a->classm("hi");
