<?php

namespace phpsonar\Anodoc\Collection;

use phpsonar\Anodoc\Tags\Tag;

class Collection implements \ArrayAccess, \Countable, \SeekableIterator
{

    private $store = [];

    public function __construct(array $array = [])
    {
        $this->store = array_merge($this->store, $array);
    }

    public function offsetExists($key)
    {
        return isset($this->store[$key]);
    }

    public function offsetGet($key)
    {
        return $this->store[$key];
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->store[] = $value;
        } else {
            $this->store[$key] = $value;
        }
    }

    public function isKeySet($key)
    {
        return $this->offsetExists($key);
    }

    public function offsetUnset($key)
    {
        unset($this->store[$key]);
    }

    public function seek($position)
    {
        if (isset($this->store[$position])) {
            return $this->store[$position];
        }
        throw new \OutOfBoundsException("Invalid seek position ($position)");
    }

    public function length()
    {
        return $this->count();
    }

    public function get($key)
    {
        return $this->offsetGet($key);
    }

    public function count()
    {
        return count($this->store);
    }

    public function rewind()
    {
        reset($this->store);
    }

    public function current()
    {
        return current($this->store);
    }

    public function key()
    {
        return key($this->store);
    }

    public function next()
    {
        next($this->store);
    }

    public function valid()
    {
        return key($this->store) !== null;
    }

    public function isEmpty()
    {
        return empty($this->store);
    }

    /**
     * @return array
     */
    public function getStore(): array
    {
        return $this->store;
    }

    public function getSubItem($tag_name, $sub_name)
    {
        $list = $this->getStore();
        foreach ($list as $item) {
            /** @var Tag $item */
            $_tag_name = !empty($item) ? $item->getTagName() : '';
            if ($_tag_name == $tag_name) {
                $value = $item->getValue();
                $name = !empty($value['name']) ? $value['name'] : '';
                if ($name == $sub_name) {
                    return $item;
                }
            }
        }
        return null;
    }

}