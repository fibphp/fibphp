<?php

namespace phpsonar\SonarNode\Scalar\MagicConst;

use phpsonar\SonarNode\Scalar\MagicConst;

class Method extends MagicConst
{
    public function getName() : string {
        return '__METHOD__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_Method';
    }
}
