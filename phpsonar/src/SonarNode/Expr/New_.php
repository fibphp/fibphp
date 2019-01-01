<?php

namespace phpsonar\SonarNode\Expr;

use phpsonar\SonarNode;
use phpsonar\SonarNode\Expr;

class New_ extends Expr
{
    /** @var SonarNode\Name|Expr|SonarNode\Stmt\Class_ Class name */
    public $class;
    /** @var SonarNode\Arg[] Arguments */
    public $args;

    /**
     * Constructs a function call node.
     *
     * @param SonarNode\Name|Expr|SonarNode\Stmt\Class_ $class      Class name (or class node for anonymous classes)
     * @param SonarNode\Arg[]                      $args       Arguments
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
