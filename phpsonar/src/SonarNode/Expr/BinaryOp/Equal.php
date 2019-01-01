<?php

namespace phpsonar\SonarNode\Expr\BinaryOp;

use phpsonar\SonarNode\Expr\BinaryOp;

class Equal extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '==';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_Equal';
    }
}
