<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:28
 */

namespace phpsonar\demos;

use phpsonar\Analyzer;
use phpsonar\Exception\ArgsError;
use phpsonar\Util;
use Tiny\Abstracts\AbstractClass;

class MainApp extends AbstractClass
{
    const DEFAULT_EXTS = ['.php'];

    private $_composer = [];
    private $_options = [];

    public function __construct(array $composer = [], array $options = [])
    {
        $this->_composer = $composer;
        $this->_options = self::fixOption($options);
    }

    public static function fixOption(array $options = [])
    {
        if (!empty($options['exts']) && is_string($options['exts'])) {
            $options['exts'] = Util::build_map_set(explode(',', $options['exts']), true);
        }
        $options['exts'] = !empty($options['exts']) ? $options['exts'] : self::DEFAULT_EXTS;
        $options['exts'] = array_map(function ($ext) {
            return Util::str_startwith($ext, '.') ? $ext : ".{$ext}";
        }, $options['exts']);
        return $options;
    }

    public static function parserArgs(array $args = []): array
    {
        return $args;
    }

    /**
     * @param string $fileOrDir
     * @param string $outPutDir
     * @throws ArgsError
     * @throws \phpsonar\Exception\ParserError
     */
    public function start(string $fileOrDir, string $outPutDir)
    {
        $rootPath = $fileOrDir;
        if (is_file($fileOrDir)) {
            $rootPath = dirname($fileOrDir);
            $fileList = [$fileOrDir];
        } elseif (is_dir($fileOrDir)) {
            $fileList = $this->_walkSubFiles($rootPath);
        } else {
            throw new ArgsError("input:{$fileOrDir} must file or dir");
        }

        $analyzer = new Analyzer($rootPath, $this->_composer, $this->_options);

        try {
            $analyzer->analyze($fileList);
        } finally {
            $analyzer->finish();
        }

        $this->generateHtml($analyzer, $outPutDir);

    }

    private function _walkSubFiles(string $rootPath)
    {
        $vendor = $rootPath . 'vendor' . DIRECTORY_SEPARATOR;
        $exts = Util::v($this->_options, 'exts', self::DEFAULT_EXTS);
        return Util::scanFiles($rootPath, function ($path) use ($exts) {
            $ext = Util::getObjectExt($path);
            $ext = strtolower($ext);
            return in_array($ext, $exts);
        }, function ($path) use($vendor){
            return !Util::stri_startwith($path, $vendor);
        });
    }

    public function generateHtml(Analyzer $analyzer, string $outPutDir)
    {
    }

}