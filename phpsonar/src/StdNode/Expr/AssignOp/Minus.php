<?php

namespace phpsonar\StdNode\Expr\AssignOp;

use phpsonar\StdNode\Expr\AssignOp;

class Minus extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Minus';
    }
}
