<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:35
 */

namespace phpsonar\Types;

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

    /** @var MethodType[] $_methods */
    protected $_methods = [];

    
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