<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\16 0016
 * Time: 18:12
 */

namespace phpsonar\Exception;


use Tiny\Exception\Error;

class ParserError extends Error
{
    protected static $errno = 540;
}