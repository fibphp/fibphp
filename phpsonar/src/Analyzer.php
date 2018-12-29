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
use phpsonar\Exception\ReDefineWarn;
use Tiny\Abstracts\AbstractClass;

class Analyzer extends AbstractClass
{
    private $_rootPath = '';
    private $_composer = [];
    private $_options = [];

    private $_ast_map = [];

    public static $log_level = 'WARN';

    public function __construct(string $rootPath, array $composer = [], array $options = [])
    {
        $this->_rootPath = $rootPath;
        $this->_composer = $composer;
        $this->_options = $options;
    }

    public static function buildCodeAtMsg(CodeAt $codeAt = null)
    {
        $code_str = '';
        if (!empty($codeAt)) {
            $code_str = " in " . $codeAt->getFile();
            $line = $codeAt->getStartLine();
            if ($line >= 0) {
                $code_str .= " on line {$line}";
            }
            $offset = $codeAt->getStartTokenPos();
            if ($offset >= 0) {
                $code_str .= " offset {$offset}";
            }
        }
        return $code_str;
    }

    const LOG_LEVEL_DICT = [
        'ALL' => 0,
        'DEBUG' => 10,
        'INFO' => 20,
        'WARN' => 30,
        'ERROR' => 40,
        'FATAL' => 50,
        'OFF' => 60,
    ];

    public static function _log($msg, $tag = 'info', $newline = false)
    {
        $tag = strtoupper($tag);
        $level = isset(self::LOG_LEVEL_DICT[$tag]) ? self::LOG_LEVEL_DICT[$tag] : -1;
        $level = ($level >= 10 && $level <= 50) ? $level : -10;
        $level_need = isset(self::LOG_LEVEL_DICT[self::$log_level]) ? self::LOG_LEVEL_DICT[self::$log_level] : 30;  //未指定日志级别时只记录WARN及以上信息

        $msg_log = '';
        if ($level < $level_need) {  //级别低于当前级别直接返回空字符串
            return $msg_log;
        }

        if ($newline) {
            $msg_log = "\n" . date('Y-m-d H:i:s') . " [{$tag}] " . $msg . "\n";
        } else {
            $msg_log = date('Y-m-d H:i:s') . " [{$tag}] " . $msg . "\n";
        }
        echo $msg_log;
        return $msg_log;
    }

    public static function _logState(State $state)
    {
        $state->walkError(function ($tag, $key, $err) use ($state) {
            false && func_get_args();
            $tag = strtoupper($tag);
            /** @var PhpSonarError $err */
            $code_str = self::buildCodeAtMsg($err->getCodeAt());
            $log_msg = get_class($err) . " " . $err->getMessage() . $code_str;
            if ($err instanceof ReDefineWarn) {
                $global_map = $state->getGlobalMap();
                if (!empty($global_map)) {
                    $code_at = $global_map->tryGetCodeAt($key);
                    if (!empty($code_at)) {
                        $code_msg = self::buildCodeAtMsg($code_at);
                        $log_msg .= "\n\t\t\t\t\t\t\t {$key} first define{$code_msg}";
                    }
                }
            }
            self::_log($log_msg, $tag);
        });
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
            $state->setCurNamespace('');

            $used = round(microtime(true) - $start, 3) * 1000;
            self::_log("analyzeStd {$name} done, use:{$used}ms  =>  {$path}");
            self::_logState($state);
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
            $state->setCurNamespace('');

            $used = round(microtime(true) - $start, 2) * 1000;
            self::_log("analyze {$name} done, use:{$used}ms  =>  {$path}");
            self::_logState($state);
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
                error_log("{$name} Parse error: {$error->getMessage()}");
            }
        }

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