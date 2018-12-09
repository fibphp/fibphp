<?php

$a = "hello";
$k = 2;
while ($k <= 3) {
    $b = $a;
    echo $b;
    $k = $k + 1;
    $a = 1;
}
echo $a, $b;
