<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\18 0018
 * Time: 23:45
 */

namespace phpsonar\Exception;

use Exception;
use phpsonar\CodeAt;

class ReDefineWarn extends PhpSonarError
{
    protected static $errno = 310;

    private $_name = '';

    public function __construct($message, Exception $previous = null, CodeAt $codeAt = null, $name = '')
    {
        parent::__construct($message, $previous, $codeAt);
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->_name = $name;
    }
}