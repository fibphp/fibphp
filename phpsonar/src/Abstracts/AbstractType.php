<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:02
 */

namespace phpsonar\Abstracts;


use PhpParser\Node;
use phpsonar\Types\BaseTypes\FloatType;
use phpsonar\Types\BaseTypes\NumberType;
use phpsonar\Types\BaseTypes\StringType;
use phpsonar\Types\MixedType;

abstract class AbstractType
{
    protected $_node = null;

    public function __construct(Node $node = null)
    {
        $this->_node = $node;
    }

    /**
     * @return null|Node
     */
    public function getNode()
    {
        return $this->_node;
    }

    public static function judgeType(Node $node){
        if($node instanceof Node\Scalar\LNumber){
            return new NumberType($node);
        } elseif( $node instanceof Node\Scalar\DNumber){
            return new FloatType($node);
        } elseif($node instanceof Node\Scalar\String_){
            return new StringType($node);
        }

        return new MixedType($node);
    }

}