<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:28
 */

namespace phpsonar\demos;

use phpsonar\Util;
use Tiny\Abstracts\AbstractClass;

class Demo extends AbstractClass
{

    public static function parserArgs(array $args = [])
    {
        $ret = [
            '__args__' => []
        ];
        foreach ($args as $idx => $key) {
            if ($idx == 0) {
                $ret['script'] = $key;
                continue;
            }
            if (Util::str_startwith($key, '--')) {

            } elseif (Util::str_startwith($key, '-')) {
                $key = substr(                              )
                $ret[]
            } else {
                $ret['__args__'][] = $key;
            }
        }
        /*

    String key = args[i];
        if (key.startsWith("--")) {
            if (i + 1 >= args.length) {
                $.die("option needs a value: " + key);
            } else {
                key = key.substring(2);
                String value = args[i + 1];
                if (!value.startsWith("-")) {
                    optionsMap.put(key, value);
                    i++;
                }
            }
        } else if (key.startsWith("-")) {
            key = key.substring(1);
            optionsMap.put(key, true);
        } else {
            this.args.add(key);
        }
    } */
        return $ret;
    }

    public
    function start(string $fileOrDir, string $outPutDir, array $options = [])
    {

    }
}