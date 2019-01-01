<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 16:16
 */

namespace phpsonar\Types\Wrapper;


use phpsonar\Types\MixedType;

abstract class AbstractWrapper extends MixedType
{
    /** @var MixedType $_type */
    protected $_type = null;

    public function __construct(MixedType $type)
    {
        parent::__construct($type->getNode(), $type->getName());
        $this->_type = $type;
    }

}