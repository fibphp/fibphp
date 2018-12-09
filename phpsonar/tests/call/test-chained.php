<?php

class A
{
    public function foo($x, $y)
    {
        $t = $x + 1;
        return $this;
    }
}

$a = new A();
$y = $a->foo(1, 2)->foo(2, 3)->foo(3, 4)->foo(4, 5)->foo(1, 2)->foo(2, 3)->foo(3, 4)->foo(4, 5)->foo(1, 2)->foo(2, 3)->foo(3, 4)->foo(4, 5)->foo(1, 2)->foo(2, 3)->foo(3, 4)->foo(4, 5)->foo(1, 2)->foo(2, 3)->foo(3, 4)->foo(4, 5);
var_dump($y);