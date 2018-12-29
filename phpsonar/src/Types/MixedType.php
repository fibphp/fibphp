<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\19 0019
 * Time: 2:21
 */

namespace phpsonar\Types;

use PhpParser\Node;
use phpsonar\Abstracts\AbstractType;

class MixedType extends AbstractType
{

    protected $_name = 'mixed';

    public function __construct(Node $node, string $name = '')
    {
        parent::__construct($node);
        $this->_name = !empty($name) ? $name : $this->_name;
    }

    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

}