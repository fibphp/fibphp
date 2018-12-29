<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:39
 */

namespace phpsonar\Types;


use PhpParser\Node;

class InterfaceType extends MixedType
{

    protected $_name = '__INTERFACE__';

    /** @var InterfaceType[] $_implements */
    protected $_implements = [];

    /** @var MethodType[] $_methods */
    protected $_methods = [];

    public function __construct(Node $node, string $name = '', array $implements = [], array $methods = [])
    {
        parent::__construct($node, $name);
        $this->_implements = $implements;
        $this->_methods = $methods;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return MethodType[]
     */
    public function getMethods(): array
    {
        return $this->_methods;
    }

    /**
     * @return InterfaceType[]
     */
    public function getImplements(): array
    {
        return $this->_implements;
    }

}