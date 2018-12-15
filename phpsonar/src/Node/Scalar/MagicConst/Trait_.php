<?php

namespace phpsonar\Node\Scalar\MagicConst;

use phpsonar\Node\Scalar\MagicConst;

class Trait_ extends MagicConst
{
    public function getName() : string {
        return '__TRAIT__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_Trait';
    }
}
