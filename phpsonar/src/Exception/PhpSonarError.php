<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 13:10
 */

namespace phpsonar\Exception;

use Exception;
use phpsonar\CodeAt;
use Tiny\Exception\Error;

class PhpSonarError extends Error
{

    private $_codeAt = null;

    protected static $errno = 530;

    public function __construct($message, Exception $previous = null, CodeAt $codeAt = null)
    {
        parent::__construct($message, $previous);
        $this->_codeAt = $codeAt;
    }

    /**
     * @return CodeAt
     */
    public function getCodeAt()
    {
        return $this->_codeAt;
    }

    /**
     * @param null $codeAt
     */
    public function setCodeAt($codeAt): void
    {
        $this->_codeAt = $codeAt;
    }

}