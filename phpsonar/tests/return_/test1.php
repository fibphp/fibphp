<?php

$u = 1;
$v = 'hi';

function f($x, $y = null)
{
    global $u, $v;
    if ($x < 5) {
        return $u;
    } else {
        echo $v;
    }
    return true;
    $y = 'hi';
    echo $y;
}


$y = f(42);
echo $y;
