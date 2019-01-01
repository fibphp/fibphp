<?php

namespace phpsonar\Anodoc;

use phpsonar\Anodoc\Collection\TagGroup;
use phpsonar\Anodoc\Collection\TagGroupCollection;

class DocComment
{

    private $description = '';
    private $tags = [];

    /**
     * DocComment constructor.
     * @param string $description
     * @param TagGroupCollection|null $tags
     * @throws Collection\NotATagGroupException
     */
    public function __construct($description = '', TagGroupCollection $tags = null)
    {
        if (!$tags) {
            $tags = new TagGroupCollection;
        }
        $this->description = $description;
        foreach ($tags as $tag => $value) {
            $this->tags[$tag] = $value;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getShortDescription()
    {
        if (preg_match('/^(.+)\n/', $this->description, $matches)) {
            return $matches[1];
        }
        return $this->description;
    }

    public function getLongDescription()
    {
        if ($longDescription = preg_replace('/^.+\n/', '', $this->description)) {
            return $longDescription != $this->description ?
                trim($longDescription) : '';
        }
        return '';
    }

    /**
     * @param $tag
     * @return mixed|TagGroup
     * @throws Collection\NotATagException
     */
    public function getTags($tag)
    {
        if ($this->hasTag($tag)) {
            return $this->tags[$tag];
        }
        return new TagGroup($tag);
    }

    public function getTag($tag)
    {
        if ($this->hasTag($tag)) {
            return $this->tags[$tag][$this->tags[$tag]->count() - 1];
        }
        return new Tags\NullTag('', '');
    }

    public function getTagValue($tag)
    {
        if ($this->hasTag($tag)) {
            /** @var mixed $last */
            $last = $this->tags[$tag][$this->tags[$tag]->count() - 1];
            return $last->getValue();
        }
        return null;
    }

    public function hasTag($tag)
    {
        return isset($this->tags[$tag]);
    }
}
