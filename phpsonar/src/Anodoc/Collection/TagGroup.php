<?php

namespace phpsonar\Anodoc\Collection;

use phpsonar\Anodoc\Tags\Tag;

class TagGroup extends Collection
{

    private $tag_name = '';

    /**
     * TagGroup constructor.
     * @param $tag_name
     * @param array $array
     * @throws NotATagException
     */
    public function __construct($tag_name, $array = [])
    {
        parent::__construct();
        $this->tag_name = $tag_name;
        foreach ($array as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    public function getTagName()
    {
        return $this->tag_name;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @throws NotATagException
     */
    public function offsetSet($key, $value)
    {
        if ($value instanceof Tag) {
            parent::offsetSet($key, $value);
        } else {
            throw new NotATagException("Offset '$key' is not a tag");
        }
    }

}