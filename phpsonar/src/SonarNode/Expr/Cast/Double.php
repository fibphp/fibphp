<?php

namespace phpsonar\SonarNode\Expr\Cast;

use phpsonar\SonarNode\Expr\Cast;

class Double extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Double';
    }
}
