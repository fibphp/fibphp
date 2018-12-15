<?php

namespace phpsonar\Node\Expr\AssignOp;

use phpsonar\Node\Expr\AssignOp;

class Minus extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Minus';
    }
}
