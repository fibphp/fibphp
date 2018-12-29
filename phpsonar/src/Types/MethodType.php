<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 14:46
 */

namespace phpsonar\Types;


class MethodType extends FunctionType
{

    protected $_name = '__METHOD__';

    /** @var ClassType $_this */
    protected $_this = null;

    /**
     * @return ClassType
     */
    public function getThis(): ClassType
    {
        return $this->_this;
    }

}