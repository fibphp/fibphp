<?php

namespace phpsonar\StdNode\Expr\Cast;

use phpsonar\StdNode\Expr\Cast;

class Int_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Int';
    }
}
