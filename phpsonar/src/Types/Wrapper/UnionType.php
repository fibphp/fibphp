<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\1\1 0001
 * Time: 20:55
 */

namespace phpsonar\Types\Wrapper;


use phpsonar\Types\MixedType;

class UnionType extends MixedType
{
    /** @var MixedType[] $_types */
    protected $_types = null;

    public function __construct(array $types)
    {
        $type = !empty($types) ? $types[0] : null;
        parent::__construct(!empty($type) ? $type->getNode() : null, !empty($type) ? $type->getName() : '');
        $this->_types = $types;
    }
}