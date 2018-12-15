<?php

namespace phpsonar\Node\Expr\BinaryOp;

use phpsonar\Node\Expr\BinaryOp;

class Mod extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '%';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_Mod';
    }
}
