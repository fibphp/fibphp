<?php

namespace phpsonar\Node\Expr;

use phpsonar\Node\Expr;

class Empty_ extends Expr
{
    /** @var Expr Expression */
    public $expr;

    /**
     * Constructs an empty() node.
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
        return 'Expr_Empty';
    }
}
