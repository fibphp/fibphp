<?php

namespace phpsonar\SonarNode\Expr\Cast;

use phpsonar\SonarNode\Expr\Cast;

class String_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_String';
    }
}
