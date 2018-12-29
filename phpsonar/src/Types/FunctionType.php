<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:01
 */

namespace phpsonar\Types;

use PhpParser\Node;

class FunctionType extends MixedType
{

    protected $_name = '__FUNCTION__';

    /** @var ParamsType $_param */
    protected $_param = [];

    /** @var ReturnType $_return */
    protected $_return = null;

    public function __construct(Node $node, string $name = '', ParamsType $param = null, ReturnType $return = null)
    {
        parent::__construct($node, $name);
        $this->_param = $param;
        $this->_return = $return;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return ParamsType
     */
    public function getParam(): ParamsType
    {
        return $this->_param;
    }

    /**
     * @return ReturnType
     */
    public function getReturn(): ReturnType
    {
        return $this->_return;
    }


}