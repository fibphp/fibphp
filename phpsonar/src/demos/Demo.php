<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:28
 */

namespace phpsonar\demos;

use phpsonar\Util;
use Tiny\Abstracts\AbstractClass;

class Demo extends AbstractClass
{

    public static function parserArgs(array $args = []) :array
    {
        return $args;
    }

    public function start(string $fileOrDir, string $outPutDir, array $composerMap, array $options = [])
    {

    }
}