<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 15:28
 */

namespace phpsonar\Types;


use PhpParser\Node;

class TraitType extends MixedType
{

    protected $_name = '__TRAIT__';

    /** @var TraitType[] $_traits */
    protected $_traits = [];

    /** @var PropertyDict $_property */
    protected $_property = null;

    /** @var MethodType[] $_methods */
    protected $_methods = [];

    public function __construct(Node $node, $name = '', array $traits = [], PropertyDict $property = null, array $methods = [])
    {
        parent::__construct($node, $name);
        $this->_traits = $traits;
        $this->_property = $property;
        $this->_methods = $methods;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return TraitType[]
     */
    public function getTraits(): array
    {
        return $this->_traits;
    }

    /**
     * @return PropertyDict
     */
    public function getProperty(): PropertyDict
    {
        return $this->_property;
    }

    /**
     * @return MethodType[]
     */
    public function getMethods(): array
    {
        return $this->_methods;
    }

}