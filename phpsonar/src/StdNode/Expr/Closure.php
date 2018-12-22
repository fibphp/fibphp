<?php

namespace phpsonar\StdNode\Expr;

use phpsonar\StdNode;
use phpsonar\StdNode\Expr;
use phpsonar\StdNode\FunctionLike;

class Closure extends Expr implements FunctionLike
{
    /** @var bool Whether the closure is static */
    public $static;
    /** @var bool Whether to return by reference */
    public $byRef;
    /** @var StdNode\Param[] Parameters */
    public $params;
    /** @var ClosureUse[] use()s */
    public $uses;
    /** @var null|StdNode\Identifier|StdNode\Name|StdNode\NullableType Return type */
    public $returnType;
    /** @var StdNode\Stmt[] Statements */
    public $stmts;

    /**
     * Constructs a lambda function node.
     *
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'static'     => false  : Whether the closure is static
     *                          'byRef'      => false  : Whether to return by reference
     *                          'params'     => array(): Parameters
     *                          'uses'       => array(): use()s
     *                          'returnType' => null   : Return type
     *                          'stmts'      => array(): Statements
     * @param array $attributes Additional attributes
     */
    public function __construct(array $subNodes = [], array $attributes = []) {
        parent::__construct($attributes);
        $this->static = $subNodes['static'] ?? false;
        $this->byRef = $subNodes['byRef'] ?? false;
        $this->params = $subNodes['params'] ?? [];
        $this->uses = $subNodes['uses'] ?? [];
        $returnType = $subNodes['returnType'] ?? null;
        $this->returnType = \is_string($returnType) ? new StdNode\Identifier($returnType) : $returnType;
        $this->stmts = $subNodes['stmts'] ?? [];
    }

    public function getSubNodeNames() : array {
        return ['static', 'byRef', 'params', 'uses', 'returnType', 'stmts'];
    }

    public function returnsByRef() : bool {
        return $this->byRef;
    }

    public function getParams() : array {
        return $this->params;
    }

    public function getReturnType() {
        return $this->returnType;
    }

    /** @return StdNode\Stmt[] */
    public function getStmts() : array {
        return $this->stmts;
    }
    
    public function getType() : string {
        return 'Expr_Closure';
    }
}
