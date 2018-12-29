<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 16:03
 */

namespace phpsonar\Types;


class ParamsType extends MixedType
{

    protected $_name = '__PARAMS__';

    /** @var bool $_variable_arg_type */
    protected $_variable_arg_list = false;
    /** @var MixedType $_variable_arg_type */
    protected $_variable_arg_type = null;

    /** @var bool $_func_with_arg */
    protected $_func_with_arg = false;

}