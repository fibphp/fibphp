<?php

namespace phpsonar\Anodoc;

use phpsonar\Anodoc\Collection\TagGroup;
use phpsonar\Anodoc\Collection\TagGroupCollection;
use phpsonar\Anodoc\Tags\GenericTag;

class Parser
{

    private $registered_tags = [];

    /**
     * @param $doc_comment
     * @return DocComment
     * @throws Collection\NotATagException
     * @throws Collection\NotATagGroupException
     */
    public function parse($doc_comment)
    {
        $doc_comment = $this->cleanupLines($doc_comment);
        $lines = $this->getLines($doc_comment);
        $description = $this->getDescription($lines);
        return new DocComment(trim($description), $this->getTags($lines));
    }

    public function getLines($doc_comment)
    {
        return ($lines = preg_split('/\s*\n\s*/', $doc_comment)) ? $lines : [];
    }

    private function cleanupLines($str)
    {
        return preg_replace(
            ['/^\/\**/', '/\n[\/\* ]+\**/'], ['', "\n"], $str
        );
    }

    private function getDescription(&$lines)
    {
        $description = '';
        while (
            is_string($line = array_shift($lines))
            && !$this->startsWithTag($line)
        ) {
            $description .= "$line\n";
        }
        array_unshift($lines, $line);
        return $description;
    }

    public function registerTag($tag_name, $tag_class)
    {
        $this->registered_tags[$tag_name] = $tag_class;
    }

    /**
     * @param $lines
     * @return TagGroupCollection
     * @throws Collection\NotATagException
     * @throws Collection\NotATagGroupException
     */
    private function getTags($lines)
    {
        $raw_tags = $this->getTagsRaw($lines);
        $tags = new TagGroupCollection;
        foreach ($raw_tags as $tag_name => $values) {
            if (!$tags->isKeySet($tag_name)) {
                $tags[$tag_name] = new TagGroup($tag_name);
            }
            foreach ($values as $value) {
                if (isset($this->registered_tags[$tag_name])) {
                    $tag_class = $this->registered_tags[$tag_name];
                    $tags[$tag_name][] = new $tag_class($tag_name, $value);
                } else {
                    $tags[$tag_name][] = new GenericTag($tag_name, $value);
                }

            }
        }
        return $tags;
    }

    private function getTagsRaw($lines)
    {
        $tags = [];
        $tag_found = false;

        $current_tag = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if (preg_match('/^@(\w+)\s*(.*)$/', $line, $line_parsed)) {
                $current_tag = $line_parsed[1];
                if (!isset($tags[$current_tag])) {
                    $tags[$current_tag] = [];
                }
                $tags[$current_tag][] = $line_parsed[2];
                $tag_found = true;
            } elseif ($tag_found) {
                $tag_value = array_pop($tags[$current_tag]);
                $tags[$current_tag][] .= $tag_value . "\n" . $line;

            }
        }
        return $tags;
    }

    public function startsWithTag($line)
    {
        return preg_match('/^@\w/', $line);
    }

}
