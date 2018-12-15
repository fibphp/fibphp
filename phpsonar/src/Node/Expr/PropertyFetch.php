<?php

namespace phpsonar\Node\Expr;

use phpsonar\Node\Expr;
use phpsonar\Node\Identifier;

class PropertyFetch extends Expr
{
    /** @var Expr Variable holding object */
    public $var;
    /** @var Identifier|Expr Property name */
    public $name;

    /**
     * Constructs a function call node.
     *
     * @param Expr                   $var        Variable holding object
     * @param string|Identifier|Expr $name       Property name
     * @param array                  $attributes Additional attributes
     */
    public function __construct(Expr $var, $name, array $attributes = []) {
        parent::__construct($attributes);
        $this->var = $var;
        $this->name = \is_string($name) ? new Identifier($name) : $name;
    }

    public function getSubNodeNames() : array {
        return ['var', 'name'];
    }
    
    public function getType() : string {
        return 'Expr_PropertyFetch';
    }
}
