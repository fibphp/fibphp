<?php

$a = [];
$a[0] = 1;
var_dump($a);

$b = [];
$b[0] = "hello";
$x = $b[1];
var_dump($x);

$c = [];
$c[] = 1;
$z = $c[0];
var_dump($z);

$d = [];
$d['x'] = 10;
$d['y'] = true;
$u = $d['foo'];
var_dump($u);
