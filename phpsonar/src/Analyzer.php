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
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Tiny\Abstracts\AbstractClass;

class Analyzer extends AbstractClass
{
    private $_rootPath = '';
    private $_composer = [];
    private $_options = [];

    public function __construct(string $rootPath, array $composer = [], array $options = [])
    {
        $this->_rootPath = $rootPath;
        $this->_composer = $composer;
        $this->_options = $options;
    }

    public function analyze(array $fileList)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        foreach ($fileList as $path => $name) {
            $this->loadFile($parser, $path, $name);
        }
    }

    public function loadFile(Parser $parser, $file, $name)
    {
        $code = file_get_contents($file);
        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo "{$name} Parse error: {$error->getMessage()}\n";
            return;
        }

        $dumper = new NodeDumper;
        echo $dumper->dump($ast) . "\n";

    }

    public function finish()
    {
    }

}