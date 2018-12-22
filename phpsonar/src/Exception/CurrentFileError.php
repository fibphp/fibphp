<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\22 0022
 * Time: 19:04
 */

namespace phpsonar\Exception;


use Tiny\Exception\Error;

class CurrentFileError extends Error
{
    protected static $errno = 595;
}