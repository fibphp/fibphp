<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:02
 */

namespace phpsonar\Abstracts;


use PhpParser\Node;

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

}