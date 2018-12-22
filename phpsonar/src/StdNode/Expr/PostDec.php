<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode\Expr;

class PostDec extends Expr
{
    /** @var Expr Variable */
    public $var;

    /**
     * Constructs a post decrement node.
     *
     * @param Expr  $var        Variable
     * @param array $attributes Additional attributes
     */
    public function __construct(Expr $var, array $attributes = []) {
        parent::__construct($attributes);
        $this->var = $var;
    }

    public function getSubNodeNames() : array {
        return ['var'];
    }
    
    public function getType() : string {
        return 'Expr_PostDec';
    }
}
