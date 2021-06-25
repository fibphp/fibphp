<?php


require_once(dirname(__DIR__) . '/vendor/autoload.php');

use php9cc\App;
use php9cc\demos\MainApp;

ini_set('display_errors', 0);
ini_set('log_errors', 1);
date_default_timezone_set('Asia/Shanghai');

$root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$cache_path = $root_path . 'cache' . DIRECTORY_SEPARATOR;


$app = App::app('app', [
    'ENVIRON' => 'debug',
    'ROOT_PATH' => $root_path,
    'CACHE_PATH' => $cache_path,
    'ENV_LOG' => [
        'path' => $root_path . 'logs' . DIRECTORY_SEPARATOR,  //日志文件存放地址
        'level' => 'DEBUG',  //日志记录级别  ['ALL' => 0, 'DEBUG' => 10, 'INFO' => 20, 'WARN' => 30, 'ERROR' => 40, 'FATAL' => 50, 'OFF' => 60,]
    ],
    'ENV_CACHE' => [
        'type' => 'files',
        'config' => [
            'path' => $cache_path
        ]
    ],
]);

$cl_json = 'D:\\php_sdk\\phpdev\\vc15\\x64\\php-src\\nmake_cl.json';
$src_dir = 'D:\\php_sdk\\phpdev\\vc15\\x64\\php-src';
$data = json_decode(file_get_contents($cl_json), true);
foreach ($data as $item) {
    $options = MainApp::parserArgs([
        'src_root' => $src_dir,
        'sys_include' => [
            'C:\\Program Files (x86)\\Microsoft Visual Studio 14.0\\VC\\include',
            'C:\\Program Files (x86)\\Microsoft Visual Studio 14.0\\VC\\atlmfc\\include',
            'C:\\Program Files (x86)\\Windows Kits\\10\\Include\\10.0.17763.0\\ucrt',
            'C:\\Program Files (x86)\\Windows Kits\\10\\Include\\10.0.17763.0\\um',
            'C:\\Program Files (x86)\\Windows Kits\\10\\Include\\10.0.17763.0\\shared',
            'C:\\Program Files (x86)\\Windows Kits\\10\\Include\\10.0.17763.0\\winrt',
            'C:\\Program Files (x86)\\Windows Kits\\10\\Include\\10.0.17763.0\\cppwinrt'
        ],
        'define' => $item['define'],
        'include' => array_map(function ($v) use ($src_dir) {
            return !empty($v[1]) && $v[1] == ':' ? $v : realpath("{$src_dir}\\{$v}");
        }, $item['include'])
    ]);

    $cc = new MainApp($options);
    foreach ($item['input'] as $inputFile) {
        $inputFile = str_replace("/", "\\", $inputFile);
        $inputFile = realpath("{$src_dir}\\{$inputFile}");
        $outputFile = "{$inputFile}.ci";
        $cc->process($inputFile, $outputFile);
        break;
    }
    break;
}

