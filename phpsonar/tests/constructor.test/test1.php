<?php

class TestInit1
{
    public $my_field;

    public function test()
    {
        $x = $this->my_field;
    }

    public function test2()
    {
        $y = $this->my_field;
    }

    public function __construct($x)
    {
        $this->my_field = $x;
    }
}

# with invocation
$y = new TestInit1(5);
$z = new TestInit1(6);
$y->my_field = $z->my_field;


# without invocation
class TestInit2
{
    public $my_field;

    public function test()
    {
        $x = $this->my_field;
    }

    public function test2()
    {
        $y = $this->my_field;
    }

    public function __construct($x)
    {
        $this->my_field = $x;
    }
}


# without invocation and other methods
class TestInit3
{
    public $my_field;

    public function __construct()
    {
        $this->my_field = 3;
        echo $this->my_field;
    }
}
