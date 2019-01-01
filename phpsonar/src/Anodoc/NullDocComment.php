<?php

namespace phpsonar\Anodoc;

class NullDocComment extends DocComment
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDescription()
    {
    }

    public function getShortDescription()
    {
    }

    public function getLongDescription()
    {
    }

}
