<?php


use phpsonar\App;
use phpsonar\demos\Demo;

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

    $_cmd_ = !empty($argv[1]) ? trim($argv[1]) : 'unknown';
    switch ($_cmd_) {
        case 'build':
            $options = Demo::parserArgs($args);
            $fileOrDir = !empty($args[2]) ? trim($args[2]) : './';
            $outPutDir = !empty($args[3]) ? trim($args[3]) : './out';

            App::set_config('input', [
                'args' => $args,
                'options' => $options,
                'fileOrDir' => $fileOrDir,
                'outPutDir' => $outPutDir,
            ]);
            (new Demo())->start($fileOrDir, $outPutDir, $options);
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