<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:18
 */

namespace phpsonar;

use Tiny\Util as _Util;

class Util extends _Util
{

    public static function parseComposer(string $app_root): array
    {
        $app_root = self::str_endwith($app_root, DIRECTORY_SEPARATOR) ? $app_root : ($app_root . DIRECTORY_SEPARATOR);

        $composer_vendor = "{$app_root}vendor";
        $composer_file = "{$app_root}composer.json";
        $composer_json = file_get_contents($composer_file);

        $composer = json_decode($composer_json, true);
        $require = self::v($composer, 'require', []);
        $require_dev = self::v($composer, 'require-dev', []);
        $resole = self::resoleComposerDep(array_merge($require_dev, $require), $composer_vendor);
        $autoload = self::v($composer, 'autoload', []);
        $autoload_psr4 = self::v($autoload, 'psr-4', []);
        $autoload_dev = self::v($composer, 'autoload-dev', []);
        $autoload_psr4_dev = self::v($autoload_dev, 'psr-4', []);
        $autoload_psr4 = array_merge($autoload_psr4, $autoload_psr4_dev);

        $resole['main'] = [
            'require' => $require,
            'autoload_psr4' => self::fixAutoloadPsr4($autoload_psr4, $app_root),
        ];


        return [
            'require' => $resole,
            'autoload_psr4' => self::buildAutoloadPsr4Map($resole),
        ];
    }

    public static function buildAutoloadPsr4Map(array $resole): array
    {
        $ret = [];
        foreach ($resole as $pkg => $item) {
            $autoload_psr4 = self::v($item, 'autoload_psr4', []);
            $ret = array_merge($ret, $autoload_psr4);
        }
        return $ret;
    }

    public static function resoleComposerDep(array $require, string $composer_vendor): array
    {
        $composer_vendor = self::str_endwith($composer_vendor, DIRECTORY_SEPARATOR) ? $composer_vendor : ($composer_vendor . DIRECTORY_SEPARATOR);

        $resole_deps = [];
        $find_deps = $require;
        $new_deps = [];
        while (!(empty($require) && empty($new_deps))) {
            $new_deps = [];
            foreach ($find_deps as $pkg => $ver) {
                unset($require[$pkg]);
                $pkg_ = str_replace('/', DIRECTORY_SEPARATOR, $pkg);
                $pkg_root = "{$composer_vendor}{$pkg_}" . DIRECTORY_SEPARATOR;
                $composer_file = "{$pkg_root}composer.json";
                if (!empty($resole_deps[$pkg]) || !is_file($composer_file)) {
                    continue;
                }
                $composer_json = file_get_contents($composer_file);
                $composer = json_decode($composer_json, true);
                $require = self::v($composer, 'require', []);
                $require_dev = self::v($composer, 'require-dev', []);
                $autoload = self::v($composer, 'autoload', []);
                $autoload_psr4 = self::v($autoload, 'psr-4', []);
                $require = array_merge($require_dev, $require);
                $new_deps = array_merge($new_deps, $require);
                $resole_deps[$pkg] = [
                    'require' => $require,
                    'autoload_psr4' => self::fixAutoloadPsr4($autoload_psr4, $pkg_root),
                ];
            }
            $find_deps = array_merge($find_deps, $new_deps);
        }

        foreach ($find_deps as $pkg => $ver) {
            if (empty($resole_deps[$pkg])) {
                $resole_deps[$pkg] = [
                    'require' => [],
                    'autoload_psr4' => [],
                ];
            }
        }
        return $resole_deps;
    }

    public static function fixAutoloadPsr4(array $autoload_psr4, string $pkg_root): array
    {
        $pkg_root = self::str_endwith($pkg_root, DIRECTORY_SEPARATOR) ? $pkg_root : ($pkg_root . DIRECTORY_SEPARATOR);
        $ret = [];
        foreach ($autoload_psr4 as $pre => $path) {
            if (is_array($path)) {
                $path = $path[0];
            }
            $path = str_replace(['/', "\\"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
            $ret[$pre] = "{$pkg_root}{$path}";
        }
        return $ret;
    }
}