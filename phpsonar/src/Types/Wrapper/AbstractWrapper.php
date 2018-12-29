<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 16:16
 */

namespace phpsonar\Types\Wrapper;


use phpsonar\Abstracts\AbstractType;
use phpsonar\Types\MixedType;

abstract class AbstractWrapper extends AbstractType
{
    /** @var MixedType $_this */
    protected $_type = null;

    public function __construct(MixedType $type)
    {
        parent::__construct($type->getNode());
        $this->_type = $type;
    }

}