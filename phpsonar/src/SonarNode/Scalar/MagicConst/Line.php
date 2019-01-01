<?php

namespace phpsonar\SonarNode\Scalar\MagicConst;

use phpsonar\SonarNode\Scalar\MagicConst;

class Line extends MagicConst
{
    public function getName() : string {
        return '__LINE__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_Line';
    }
}
