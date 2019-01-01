<?php

namespace phpsonar\SonarNode\Expr\AssignOp;

use phpsonar\SonarNode\Expr\AssignOp;

class Mod extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Mod';
    }
}
