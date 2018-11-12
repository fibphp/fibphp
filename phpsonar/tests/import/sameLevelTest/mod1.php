<?php

require_once(dirname(dirname(dirname(dirname(__DIR__)))). '/vendor/autoload.php');

use \phpsonarTests\import\sameLevelTest\B;
use \phpsonarTests\import\sameLevelTest\foo;

# import mod2

$o = new B();
echo $o->a;

$u = foo(10);
echo $u;
