<?php

namespace phpsonar\StdNode\Expr\BinaryOp;

use phpsonar\StdNode\Expr\BinaryOp;

class LogicalOr extends BinaryOp
{
    public function getOperatorSigil() : string {
        return 'or';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_LogicalOr';
    }
}
