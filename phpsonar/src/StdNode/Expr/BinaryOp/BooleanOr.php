<?php

namespace phpsonar\StdNode\Expr\BinaryOp;

use phpsonar\StdNode\Expr\BinaryOp;

class BooleanOr extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '||';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_BooleanOr';
    }
}
