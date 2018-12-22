<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode\Expr;

class UnaryPlus extends Expr
{
    /** @var Expr Expression */
    public $expr;

    /**
     * Constructs a unary plus node.
     *
     * @param Expr $expr       Expression
     * @param array               $attributes Additional attributes
     */
    public function __construct(Expr $expr, array $attributes = []) {
        parent::__construct($attributes);
        $this->expr = $expr;
    }

    public function getSubNodeNames() : array {
        return ['expr'];
    }
    
    public function getType() : string {
        return 'Expr_UnaryPlus';
    }
}
