<?php

namespace phpsonar\Node\Expr\Cast;

use phpsonar\Node\Expr\Cast;

class Unset_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Unset';
    }
}
