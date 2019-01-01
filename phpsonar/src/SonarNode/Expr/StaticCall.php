<?php

namespace phpsonar\SonarNode\Expr;

use phpsonar\SonarNode;
use phpsonar\SonarNode\Expr;
use phpsonar\SonarNode\Identifier;

class StaticCall extends Expr
{
    /** @var SonarNode\Name|Expr Class name */
    public $class;
    /** @var Identifier|Expr Method name */
    public $name;
    /** @var SonarNode\Arg[] Arguments */
    public $args;

    /**
     * Constructs a static method call node.
     *
     * @param SonarNode\Name|Expr         $class      Class name
     * @param string|Identifier|Expr $name       Method name
     * @param SonarNode\Arg[]             $args       Arguments
     * @param array                  $attributes Additional attributes
     */
    public function __construct($class, $name, array $args = [], array $attributes = []) {
        parent::__construct($attributes);
        $this->class = $class;
        $this->name = \is_string($name) ? new Identifier($name) : $name;
        $this->args = $args;
    }

    public function getSubNodeNames() : array {
        return ['class', 'name', 'args'];
    }
    
    public function getType() : string {
        return 'Expr_StaticCall';
    }
}
