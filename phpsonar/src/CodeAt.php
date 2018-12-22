<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\22 0022
 * Time: 18:26
 */

namespace phpsonar;

use PhpParser\Node;

class CodeAt
{
    private $_file = '';
    private $_startLine = -1;
    private $_endLine = -1;
    private $_startTokenPos = -1;
    private $_endTokenPos = -1;

    public function __construct($file = '', $startLine = -1, $endLine = -1, $startTokenPos = -1, $endTokenPos = -1)
    {
        $this->_file = $file;
        $this->_startLine = $startLine;
        $this->_endLine = $endLine;
        $this->_startTokenPos = $startTokenPos;
        $this->_endTokenPos = $endTokenPos;
    }

    public static function createByNode(Analyzer $analyzer, Node $node)
    {
        return new static($analyzer->getCurrentFile(), $node->getStartLine(), $node->getEndLine(), $node->getStartTokenPos(), $node->getEndTokenPos());
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->_file;
    }

    /**
     * @param string $file
     */
    public function setFile(string $file): void
    {
        $this->_file = $file;
    }

    /**
     * @return int
     */
    public function getStartLine(): int
    {
        return $this->_startLine;
    }

    /**
     * @param int $startLine
     */
    public function setStartLine(int $startLine): void
    {
        $this->_startLine = $startLine;
    }

    /**
     * @return int
     */
    public function getEndLine(): int
    {
        return $this->_endLine;
    }

    /**
     * @param int $endLine
     */
    public function setEndLine(int $endLine): void
    {
        $this->_endLine = $endLine;
    }

    /**
     * @return int
     */
    public function getStartTokenPos(): int
    {
        return $this->_startTokenPos;
    }

    /**
     * @param int $startTokenPos
     */
    public function setStartTokenPos(int $startTokenPos): void
    {
        $this->_startTokenPos = $startTokenPos;
    }

    /**
     * @return int
     */
    public function getEndTokenPos(): int
    {
        return $this->_endTokenPos;
    }

    /**
     * @param int $endTokenPos
     */
    public function setEndTokenPos(int $endTokenPos): void
    {
        $this->_endTokenPos = $endTokenPos;
    }
}