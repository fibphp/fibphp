<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode\Expr;

class Exit_ extends Expr
{
    /* For use in "kind" attribute */
    const KIND_EXIT = 1;
    const KIND_DIE = 2;

    /** @var null|Expr Expression */
    public $expr;

    /**
     * Constructs an exit() node.
     *
     * @param null|Expr $expr       Expression
     * @param array                    $attributes Additional attributes
     */
    public function __construct(Expr $expr = null, array $attributes = []) {
        parent::__construct($attributes);
        $this->expr = $expr;
    }

    public function getSubNodeNames() : array {
        return ['expr'];
    }
    
    public function getType() : string {
        return 'Expr_Exit';
    }
}