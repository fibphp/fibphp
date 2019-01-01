<?php

namespace phpsonar\SonarNode\Expr\Cast;

use phpsonar\SonarNode\Expr\Cast;

class Array_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Array';
    }
}
