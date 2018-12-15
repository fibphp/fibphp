<?php

namespace phpsonar\Node\Expr\Cast;

use phpsonar\Node\Expr\Cast;

class Double extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Double';
    }
}
