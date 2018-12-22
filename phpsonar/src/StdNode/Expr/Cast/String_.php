<?php

namespace phpsonar\StdNode\Expr\Cast;

use phpsonar\StdNode\Expr\Cast;

class String_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_String';
    }
}
