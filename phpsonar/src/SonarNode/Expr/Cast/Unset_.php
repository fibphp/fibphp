<?php

namespace phpsonar\SonarNode\Expr\Cast;

use phpsonar\SonarNode\Expr\Cast;

class Unset_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Unset';
    }
}
