<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');


trait Foo
{
    abstract public function bar();
}

interface Foo2
{

}

class Foo3 implements Foo2
{
    use Foo;

    public function bar()
    {
        return 'bar';
    }
}


$f = new Foo3();
echo $f->bar();