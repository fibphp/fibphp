<?php

class A
{

}

$x = new A();

$y = $z = null;

if ($x instanceof A) {
    $y = $x;
} else {
    $z = $x;
}

var_dump($y);
var_dump($z);
