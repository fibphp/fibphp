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

function funcSubA($isError = false)
{
    if ($isError) {
        throw new ErrorSubA("error in funcSubA");
    }


    $ret = 'call funcSubA';
    echo "\n" . $ret;
    return $ret;
}

function funcA($isError = false, $isSubError = false, $subCatch = false, $subThrow = true)
{
    if ($isError) {
        throw new ErrorA("error in funcA");
    }

    if ($subCatch) {
        try {
            echo "\nfuncSubA done:" . funcSubA($isSubError);
        } catch (ErrorSubA $ex) {
            echo "\nErrorSubA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
            if ($subThrow) {
                throw $ex;
            }
        }
    } else {
        echo "\nfuncSubA done:" . funcSubA($isSubError);
    }

    $ret = 'call funcA';
    echo "\n" . $ret;
    return $ret;
}

function funcB($isError = false)
{
    if ($isError) {
        throw new ErrorB("error in funcB");
    }
    $ret = 'call funcB';
    echo "\n" . $ret;
    return $ret;
}

function main(array $args = [])
{
    false && func_get_args();

    echo "\n\n###### NO ERROR ######";
    echo "\nfuncA done:" . funcA();
    echo "\nfuncB done:" . funcB();

    echo "\n\n###### CATCH ERROR ######";
    try {
        echo "\nfuncA done:" . funcA(true);
    } catch (ErrorA $ex) {
        echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }
    try {
        echo "\nfuncB done:" . funcB(true);
    } catch (ErrorB $ex) {
        echo "\nErrorB:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }

    echo "\n\n###### SUB ERROR ######";
    try {
        echo "\nfuncA done:" . funcA(false, true);
    } catch (ErrorA $ex) {
        echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }

    echo "\n\n###### CATCH SUB ERROR ######";
    try {
        echo "\nfuncA done:" . funcA(false, true);
    } catch (ErrorA $ex) {
        echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }

    echo "\n\n###### CATCH SUB ERROR 2 ######";
    try {
        echo "\nfuncA done:" . funcA(false, true, true);
    } catch (ErrorA $ex) {
        echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }

    echo "\n\n###### CATCH SUB ERROR 3 ######";
    try {
        echo "\nfuncA done:" . funcA(false, true, true, false);
    } catch (ErrorA $ex) {
        echo "\nErrorA:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }

}

main($argv);