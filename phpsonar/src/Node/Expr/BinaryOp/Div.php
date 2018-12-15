<?php

namespace phpsonar\Node\Expr\BinaryOp;

use phpsonar\Node\Expr\BinaryOp;

class Div extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '/';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_Div';
    }
}
