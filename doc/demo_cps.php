<?php


class ErrorA extends \Exception
{

}

class ErrorB extends \Exception
{

}

class ErrorSubA extends ErrorA
{

}

function funcSubA(callable $cps, $isError = false)
{
    if ($isError) {
        $cps(null, new ErrorSubA("error in funcSubA"));
        return;
    }

    $ret = 'call funcSubA';
    echo "\n" . $ret;
    $cps($ret);
    return;
}

function funcA(callable $cps, $isError = false, $isSubError = false, $subCatch = false, $subThrow = true)
{
    if ($isError) {
        $cps(null, new ErrorA("error in funcA"));
        return;
    }

    $otherCode = function ($cps) {
        $ret = 'call funcA';
        echo "\n" . $ret;
        $cps($ret);
        return;
    };

    if ($subCatch) {
        $finallyCode = function () {
            echo "\nfuncSubA finally";
        };
        $cpsSub = function ($ret, Exception $ex = null) use ($cps, $subThrow, $otherCode, $finallyCode) {
            if (!is_null($ex)) {
                echo "\nErrorSubA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
                $finallyCode();
                if ($subThrow) {
                    $cps(null, $ex);
                } else {
                    $otherCode($cps);
                }
            } else {
                echo "\nfuncSubA done:" . $ret;
                $finallyCode();
                $otherCode($cps);
            }
        };
    } else {
        $cpsSub = function ($ret, Exception $ex = null) use ($cps, $otherCode) {
            if (!is_null($ex)) {
                $cps(null, $ex);
            } else {
                echo "\nfuncSubA done:" . $ret;
                $otherCode($cps);
            }
        };
    }
    funcSubA($cpsSub, $isSubError);
}

function funcB(callable $cps, $isError = false)
{
    if ($isError) {
        $cps(null, new ErrorB("error in funcB"));
        return;
    }
    $ret = 'call funcB';
    echo "\n" . $ret;
    $cps($ret);
    return;
}

function main(array $args = [])
{
    false && func_get_args();

    echo "\n\n###### NO ERROR ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            exit($ex->getMessage());
        } else {
            echo "\nfuncA done:" . $ret;
        }
    });

    funcB(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            exit($ex->getMessage());
        } else {
            echo "\nfuncB done:" . $ret;
        }
    });

    echo "\n\n###### CATCH ERROR ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, true);

    funcB(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorB:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncB done:" . $ret;
        }
    }, true);

    echo "\n\n###### SUB ERROR ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, false, true);

    echo "\n\n###### CATCH SUB NO ERROR ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, false, false, true);

    echo "\n\n###### CATCH SUB ERROR ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, false, true);

    echo "\n\n###### CATCH SUB ERROR 2 ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, false, true, true);

    echo "\n\n###### CATCH SUB ERROR 3 ######";
    funcA(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncA done:" . $ret;
        }
    }, false, true, true, false);

}

main($argv);