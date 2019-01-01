<?php

namespace phpsonar\SonarNode\Scalar\MagicConst;

use phpsonar\SonarNode\Scalar\MagicConst;

class Dir extends MagicConst
{
    public function getName() : string {
        return '__DIR__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_Dir';
    }
}
