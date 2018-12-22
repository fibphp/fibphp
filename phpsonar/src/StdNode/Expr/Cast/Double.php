<?php

namespace phpsonar\StdNode\Expr\Cast;

use phpsonar\StdNode\Expr\Cast;

class Double extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Double';
    }
}
