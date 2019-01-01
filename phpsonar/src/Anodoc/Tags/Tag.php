<?php

namespace phpsonar\Anodoc\Tags;

abstract class Tag
{

    abstract public function __construct($tag_name, $value);

    abstract public function getValue();

    abstract public function getTagName();

    abstract public function __toString();

    public function isNull()
    {
        return false;
    }

}