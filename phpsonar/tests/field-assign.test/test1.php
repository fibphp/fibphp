<?php

class A
{
    public $x = 1;
    public $y;
}

$a = new A();
$a->x = "foo";
$a->y = 2;     # create field in object here

echo $a->x, $a->y;
