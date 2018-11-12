<?php

# test

function foo($f)
{
    return [$f(1), $f(true)];
}

$id = function ($x) {
    return $x;
};

$a = foo($id);
var_dump($a);


