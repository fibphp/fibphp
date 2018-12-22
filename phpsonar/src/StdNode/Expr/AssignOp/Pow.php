<?php

namespace phpsonar\StdNode\Expr\AssignOp;

use phpsonar\StdNode\Expr\AssignOp;

class Pow extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Pow';
    }
}
