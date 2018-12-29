<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode\Expr;

class BitwiseNot extends Expr
{
    /** @var Expr Expression */
    public $expr;

    /**
     * Constructs a bitwise not node.
     *
     * @param Expr  $expr       Expression
     * @param array $attributes Additional attributes
     */
    public function __construct(Expr $expr, array $attributes = []) {
        parent::__construct($attributes);
        $this->expr = $expr;
    }

    public function getSubNodeNames() : array {
        return ['expr'];
    }
    
    public function getType() : string {
        return 'Expr_BitwiseNot';
    }
}