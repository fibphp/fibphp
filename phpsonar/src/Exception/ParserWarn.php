<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\18 0018
 * Time: 23:45
 */

namespace phpsonar\Exception;


use Tiny\Exception\Error;

class ParserWarn extends Error
{
    protected static $errno = 310;
}