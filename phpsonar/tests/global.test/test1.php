<?php

$x = 1;
$y = true;

function f()
{
    global $x;
    $x = false;
    $y = 42;
    echo $x;
    echo $y;
}


function g()
{
    $x = 'hi';
    echo $x;
    $y = 'foo';
    echo $y;
    global $x;
}


echo $y;
