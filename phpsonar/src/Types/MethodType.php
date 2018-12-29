<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 14:46
 */

namespace phpsonar\Types;


use PhpParser\Node;

class MethodType extends FunctionType
{

    protected $_name = '__METHOD__';

    /** @var ClassType $_this */
    protected $_this = null;

    public function __construct(Node $node, string $name = '', ParamsType $param = null, ReturnType $return = null, ClassType $_this = null)
    {
        parent::__construct($node, $name, $param, $return);
        $this->_this = $_this;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return ClassType
     */
    public function getThis(): ClassType
    {
        return $this->_this;
    }

}