<?php

namespace phpsonar\Node\Expr\AssignOp;

use phpsonar\Node\Expr\AssignOp;

class BitwiseXor extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_BitwiseXor';
    }
}
