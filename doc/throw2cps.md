# 使用 CPS 风格实现异常机制

## 测试代码

throw 风格

```php
<?php
class ErrorB extends \Exception
{

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
    echo "\nfuncB done:" . funcB();

    echo "\n\n###### CATCH ERROR ######";
    try {
        echo "\nfuncB done:" . funcB(true);
    } catch (ErrorB $ex) {
        echo "\nErrorB:" . $ex->getMessage() . " <" . get_class($ex) . ">";
    }
}

main($argv);
```


CPS 风格

```php
<?php
class ErrorB extends \Exception
{

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
    funcB(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            exit($ex->getMessage());
        } else {
            echo "\nfuncB done:" . $ret;
        }
    });

    echo "\n\n###### CATCH ERROR ######";
    funcB(function ($ret, Exception $ex = null) {
        if (!is_null($ex)) {
            echo "\nErrorB:" . $ex->getMessage() . " <" . get_class($ex) . ">";
        } else {
            echo "\nfuncB done:" . $ret;
        }
    }, true);

}

main($argv);

```



# 参考资料



[如何设计一门语言（八）——异步编程和CPS变换](https://www.cnblogs.com/geniusvczh/p/3219204.html)

[漫谈递归：尾递归与CPS](http://www.nowamagic.net/librarys/veda/detail/2331)