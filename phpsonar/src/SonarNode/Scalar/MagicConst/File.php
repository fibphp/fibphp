<?php

namespace phpsonar\SonarNode\Scalar\MagicConst;

use phpsonar\SonarNode\Scalar\MagicConst;

class File extends MagicConst
{
    public function getName() : string {
        return '__FILE__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_File';
    }
}
