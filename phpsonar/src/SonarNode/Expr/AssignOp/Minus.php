<?php

namespace phpsonar\SonarNode\Expr\AssignOp;

use phpsonar\SonarNode\Expr\AssignOp;

class Minus extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Minus';
    }
}
