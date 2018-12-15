<?php

namespace phpsonar\Node\Expr\AssignOp;

use phpsonar\Node\Expr\AssignOp;

class Div extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Div';
    }
}
