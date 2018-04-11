<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/2/9 0009
 * Time: 13:24
 */

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

$code = <<<'CODE'
<?php

function test($foo)
{
    var_dump($foo);
}
CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";