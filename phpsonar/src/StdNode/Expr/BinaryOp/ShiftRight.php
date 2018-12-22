<?php

namespace phpsonar\StdNode\Expr\BinaryOp;

use phpsonar\StdNode\Expr\BinaryOp;

class ShiftRight extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '>>';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_ShiftRight';
    }
}
