<?php

namespace phpsonar\Node\Expr\Cast;

use phpsonar\Node\Expr\Cast;

class String_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_String';
    }
}
