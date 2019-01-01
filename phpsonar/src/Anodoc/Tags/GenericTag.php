<?php

namespace phpsonar\Anodoc\Tags;

class GenericTag extends Tag
{

    private $tag_name = '';
    private $value = null;

    public function __construct($tag_name, $value)
    {
        $this->value = $value;
        $this->tag_name = $tag_name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTagName()
    {
        return $this->tag_name;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

}