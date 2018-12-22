<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 13:10
 */

namespace phpsonar\Exception;

use Tiny\Exception\Error;

class ArgsError extends Error
{

    protected static $errno = 630;

}