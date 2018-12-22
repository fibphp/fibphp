<?php

namespace phpsonar\StdNode\Scalar\MagicConst;

use phpsonar\StdNode\Scalar\MagicConst;

class File extends MagicConst
{
    public function getName() : string {
        return '__FILE__';
    }
    
    public function getType() : string {
        return 'Scalar_MagicConst_File';
    }
}
