<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 1:02
 */

namespace phpsonar\Abstracts;


use PhpParser\Node;

class AbstractType
{
    private $_node = null;

    public function __construct(Node $node)
    {
        $this->_node = $node;
    }
}