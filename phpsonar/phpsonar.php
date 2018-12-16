<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use phpsonar\App;
use phpsonar\demos\MainApp;

function main(array $args = [])
{
    false && func_get_args();

    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    date_default_timezone_set('Asia/Shanghai');

    $root_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    $cache_path = $root_path . 'cache' . DIRECTORY_SEPARATOR;

    App::app('app', [
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
    $curpwd = getcwd() ?: dirname(__FILE__);
    $options = MainApp::parserArgs($args);
    // TODO TEST
    $args[1] = 'build';
    $options['app_root'] = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
    $options['std_root'] = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'phpstorm-stubs' . DIRECTORY_SEPARATOR;

    $_cmd_ = !empty($args[1]) ? trim($args[1]) : 'unknown';
    switch ($_cmd_) {
        case 'build':
            $inputDir = !empty($args[2]) ? trim($args[2]) : ($curpwd . DIRECTORY_SEPARATOR);
            $outputDir = !empty($args[3]) ? trim($args[3]) : ($curpwd . DIRECTORY_SEPARATOR . 'out' . DIRECTORY_SEPARATOR);

            $app_root = $options['app_root'] ?: $inputDir;
            $composer = \phpsonar\Util::parseComposer($app_root);

            (new MainApp($composer, $options))->start($inputDir, $outputDir, $composer, $options);
            break;
        case '/h':
        case '-h':
        case '/help':
        case '--help':
        default:
            echo <<<EOT
Usage:  php phpsonar.php build <file-or-dir> <output-dir>
EOT;
            break;
    }

}

main($argv);