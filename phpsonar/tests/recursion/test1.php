<?php

class Base
{
    public function x()
    {
        return "";
    }
}

class A extends Base
{
    public function x()
    {
        return "A";
    }
}

class B extends Base
{
    public function x()
    {
        return "B";
    }
}

function f1($n)
{
    if ($n == 0) {
        $a = new A();
        return $a;
    } else {
        return f1(0)->x();
    }
}

$k = f1(1);
echo $k;
