<?php

namespace phpsonar\Anodoc\Tags;

class ParamTag extends Tag
{

    private $tag_name = '';
    private $value = [];

    public function __construct($tag_name, $value)
    {
        preg_match('/(\w+)\s+\$(\w+)\s+(.+)/ms', $value, $matches);
        $this->value = [
            'type' => $matches[1],
            'name' => $matches[2],
            'description' => $matches[3]
        ];
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