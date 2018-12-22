<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode;
use phpsonar\StdNode\Expr;

class New_ extends Expr
{
    /** @var StdNode\Name|Expr|StdNode\Stmt\Class_ Class name */
    public $class;
    /** @var StdNode\Arg[] Arguments */
    public $args;

    /**
     * Constructs a function call node.
     *
     * @param StdNode\Name|Expr|StdNode\Stmt\Class_ $class      Class name (or class node for anonymous classes)
     * @param StdNode\Arg[]                      $args       Arguments
     * @param array                           $attributes Additional attributes
     */
    public function __construct($class, array $args = [], array $attributes = []) {
        parent::__construct($attributes);
        $this->class = $class;
        $this->args = $args;
    }

    public function getSubNodeNames() : array {
        return ['class', 'args'];
    }
    
    public function getType() : string {
        return 'Expr_New';
    }
}
