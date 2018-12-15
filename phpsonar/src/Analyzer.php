<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 13:36
 */

namespace phpsonar;

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Tiny\Abstracts\AbstractClass;

class Analyzer extends AbstractClass
{
    private $_rootPath = '';
    private $_composer = [];
    private $_options = [];

    private $_ast_map = [];

    public function __construct(string $rootPath, array $composer = [], array $options = [])
    {
        $this->_rootPath = $rootPath;
        $this->_composer = $composer;
        $this->_options = $options;
    }

    public function analyze(array $fileList)
    {
        $inferencer = new TypeInferencer($this);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        foreach ($fileList as $path => $name) {
            $ast = $this->loadFile($parser, $path, $name);
            if (empty($ast)) {
                // TODO report error
                continue;
            }

            $state = new State($this);
            $inferencer->traverse($ast, $state);
        }
    }

    public function loadFile(Parser $parser, $file, $name)
    {
        if (!isset($this->_ast_map[$file])) {
            try {
                $code = file_get_contents($file);
                $ast = $parser->parse($code);
                $this->_ast_map[$file] = $ast;
            } catch (Error $error) {
                $this->_ast_map[$file] = null;
                echo "{$name} Parse error: {$error->getMessage()}\n";
            }
        }

        // $dumper = new NodeDumper;
        // echo $dumper->dump($ast) . "\n";
        return $this->_ast_map[$file];
    }

    public function finish()
    {
    }

    #################################################################################
    #################################################################################
    #################################################################################

    /**
     * @return array
     */
    public function getComposer(): array
    {
        return $this->_composer;
    }

    /**
     * @param array $composer
     */
    public function setComposer(array $composer): void
    {
        $this->_composer = $composer;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->_rootPath;
    }

    /**
     * @param string $rootPath
     */
    public function setRootPath(string $rootPath): void
    {
        $this->_rootPath = $rootPath;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->_options = $options;
    }

}