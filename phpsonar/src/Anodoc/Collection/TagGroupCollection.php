<?php

namespace phpsonar\Anodoc\Collection;

class TagGroupCollection extends Collection
{

    /**
     * TagGroupCollection constructor.
     * @param array $array
     * @throws NotATagGroupException
     */
    public function __construct($array = [])
    {
        parent::__construct();
        foreach ($array as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @throws NotATagGroupException
     */
    public function offsetSet($key, $value)
    {
        if ($value instanceof TagGroup) {
            parent::offsetSet($key, $value);
        } else {
            throw new NotATagGroupException("Offset '$key' is not a tag group");
        }
    }

}