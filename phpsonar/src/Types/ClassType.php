<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:35
 */

namespace phpsonar\Types;

use PhpParser\Node;

class ClassType extends MixedType
{

    protected $_name = '__CLASS__';

    protected $_is_abstract = false;

    /** @var ClassType $_parent */
    protected $_parent = null;

    /** @var InterfaceType[] $_implements */
    protected $_implements = [];

    /** @var TraitType[] $_traits */
    protected $_traits = [];

    /** @var PropertyDict $_property */
    protected $_property = null;

    /** @var MethodType[] $_methods */
    protected $_methods = [];

    public function __construct(Node $node, string $name = '', bool $is_abstract = false, ClassType $parent = null, array $implements = [], array $traits = [], PropertyDict $property = null, array $methods = [])
    {
        parent::__construct($node, $name);
        $this->_is_abstract = $is_abstract;
        $this->_parent = $parent;
        $this->_implements = $implements;
        $this->_traits = $traits;
        $this->_property = $property;
        $this->_methods = $methods;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################
    
    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->_is_abstract;
    }

    /**
     * @return ClassType
     */
    public function getParent(): ClassType
    {
        return $this->_parent;
    }

    /**
     * @return InterfaceType[]
     */
    public function getImplements()
    {
        return $this->_implements;
    }

    /**
     * @return TraitType[]
     */
    public function getTraits(): array
    {
        return $this->_traits;
    }

    /**
     * @return MethodType[]
     */
    public function getMethods(): array
    {
        return $this->_methods;
    }
}