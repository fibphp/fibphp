<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 16:04
 */

namespace phpsonar\Types;


use PhpParser\Node;

class ReturnType extends MixedType
{

    protected $_name = '__RETURN__';

    protected $_byRef = false;

    /** @var MixedType $_this */
    protected $_type = null;

    public function __construct(Node $node, string $name = '', MixedType $type = null, $byRef = false)
    {
        parent::__construct($node, $name);
        $this->_byRef = $byRef;
        if (is_null($type)) {
            $type = new MixedType($node);
        }
        $this->_type = $type;
    }

    /**
     * @return MixedType
     */
    public function getType(): MixedType
    {
        return $this->_type;
    }

    /**
     * @return bool
     */
    public function isByRef(): bool
    {
        return $this->_byRef;
    }

    /**
     * @param bool $byRef
     */
    public function setByRef(bool $byRef): void
    {
        $this->_byRef = $byRef;
    }

    /**
     * @param MixedType $type
     */
    public function setType(MixedType $type): void
    {
        $this->_type = $type;
    }

}