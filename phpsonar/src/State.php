<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 22:38
 */

namespace phpsonar;


use Tiny\Abstracts\AbstractClass;

class State extends AbstractClass
{
    private $_analyzer = null;

    public function __construct(Analyzer $analyzer)
    {
        $this->_analyzer = $analyzer;
    }

}