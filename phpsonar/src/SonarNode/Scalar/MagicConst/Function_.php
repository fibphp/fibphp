<?php

namespace phpsonar\SonarNode\Scalar\MagicConst;

use phpsonar\SonarNode\Scalar\MagicConst;

class Function_ extends MagicConst
{
    public function getName() : string {
        return '__FUNCTION__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_Function';
    }
}
