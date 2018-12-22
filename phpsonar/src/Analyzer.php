<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 13:36
 */

namespace phpsonar;

use PhpParser\Error;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use phpsonar\Exception\CurrentFileError;
use phpsonar\Exception\ParserError;
use phpsonar\Exception\PhpSonarError;
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

    /**
     * @param $std_root
     * @return null|GlobalMap
     * @throws CurrentFileError
     * @throws ParserError
     */
    public function analyzeStd($std_root)
    {
        $fileMap = $this->_walkStdFiles($std_root);
        $inferencer = new StdTypeInferencer($this);
        $state = new State($this);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        foreach ($fileMap as $path => $name) {
            $start = microtime(true);
            $ast = $this->loadFile($parser, $path, $name);
            if (empty($ast)) {
                throw new ParserError("{$name} empty ast");
            }
            $this->pushCurrentFile($path);
            $inferencer->traverse($ast, $state);
            $this->popCurrentFile($path);

            $used = round(microtime(true) - $start, 3) * 1000;
            $msg = "analyzeStd {$name} done, use:{$used}ms  =>  {$path}\n";
            echo date('Y-m-d H:i:s') . " [INFO] " . $msg;
            $state->walkError(function ($tag, $key, $err) {
                false && func_get_args();
                $tag = strtoupper($tag);
                /** @var PhpSonarError $err */
                $code_str = '';
                $codeAt = $err->getCodeAt();
                if (!empty($codeAt)) {
                    $code_str = " at " . $codeAt->getFile();
                    $line = $codeAt->getStartLine();
                    if ($line >= 0) {
                        $code_str .= " line:{$line}";
                    }
                    $offset = $codeAt->getStartTokenPos();
                    if ($offset >= 0) {
                        $code_str .= " offset:{$offset}";
                    }

                }
                echo date('Y-m-d H:i:s') . " [$tag] " . $err->getMessage() . $code_str . "\n";
            });
        }

        return $state->getGlobalMap();
    }

    private function _walkStdFiles(string $rootPath)
    {
        $vendor = $rootPath . 'vendor' . DIRECTORY_SEPARATOR;
        $tests = $rootPath . 'tests' . DIRECTORY_SEPARATOR;
        return Util::scanFiles($rootPath, function ($path) {
            return Util::stri_endwith($path, '.php');
        }, function ($path) use ($vendor, $tests) {
            return !Util::stri_startwith($path, $vendor) && !Util::stri_startwith($path, $tests);
        });
    }

    /**
     * @param array $fileList
     * @throws CurrentFileError
     * @throws ParserError
     */
    public function analyze(array $fileList)
    {
        $global_map = !empty($this->_options['std_root']) ? $this->analyzeStd($this->_options['std_root']) : new GlobalMap();

        $inferencer = new TypeInferencer($this);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        foreach ($fileList as $path => $name) {
            $start = microtime(true);
            $ast = $this->loadFile($parser, $path, $name);
            if (empty($ast)) {
                throw new ParserError("{$name} empty ast");
            }
            $global_map_ = clone $global_map;
            $this->pushCurrentFile($path);
            $state = new State($this, $global_map_);
            $inferencer->traverse($ast, $state);
            $this->popCurrentFile($path);
            $used = round(microtime(true) - $start, 2) * 1000;
            error_log("analyze {$name} done, use:{$used}ms  =>  {$path}");
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

    private $_currentFileStack = [];

    public function getCurrentFile()
    {
        return !empty($this->_currentFileStack) ? $this->_currentFileStack[count($this->_currentFileStack) - 1] : '';
    }

    public function pushCurrentFile($file)
    {
        $this->_currentFileStack[] = $file;
    }

    /**
     * @param string $path
     * @return mixed|string
     * @throws CurrentFileError
     */
    public function popCurrentFile($path = '')
    {
        $_path = !empty($this->_currentFileStack) ? array_pop($this->_currentFileStack) : '';
        if (!empty($path) && $path != $_path) {
            throw new CurrentFileError("popCurrentFile but not match {$path} -> {$_path}");
        }
        return $_path;
    }

}