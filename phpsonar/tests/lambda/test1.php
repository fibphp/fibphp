<?php
function foo($f){
    $y = $f(1, "hi");
    return $y;
}

$z = foo(function ($a, $b){
    return [$a, $b];
});

var_dump($z);

$w = (function($f){
    return $f(1);
})(function ($x){
    return $x + 1;
});

var_dump($w);