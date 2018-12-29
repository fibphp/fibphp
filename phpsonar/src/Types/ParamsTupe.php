<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/12/29 0029
 * Time: 16:03
 */

namespace phpsonar\Types;


use PhpParser\Node;
use phpsonar\Exception\PhpSonarError;

class ParamsType extends MixedType
{

    protected $_name = '__PARAMS__';

    /** @var bool $_variable_arg_type */
    protected $_variable_arg_list = false;
    /** @var MixedType $_variable_arg_type */
    protected $_variable_arg_type = null;
    /** @var string $_variable_arg_name */
    protected $_variable_arg_name = '';

    /** @var bool $_func_with_arg */
    protected $_func_with_arg = false;

    /** @var string[] $_args */
    protected $_args = [];

    protected $_params_map = [];

    public function __construct(Node $node, string $name = '')
    {
        parent::__construct($node, $name);
    }

    public function setVariableArgInfo(string $variable_arg_name = '', MixedType $variable_arg_type = null, bool $variable_arg_list = true)
    {
        $this->_variable_arg_name = $variable_arg_name;
        $this->_variable_arg_type = $variable_arg_type;
        $this->_variable_arg_list = $variable_arg_list;
    }

    public function addParam(string $name, MixedType $type)
    {
        if (empty($name) || empty($type)) {
            throw new PhpSonarError("错误的参数");
        }
        $this->_args[] = $name;
        $this->_params_map[$name] = $type;
    }
    
    ##########################################################################
    ##########################  getter and setter  ###########################
    ##########################################################################

    /**
     * @return bool
     */
    public function isVariableArgList(): bool
    {
        return $this->_variable_arg_list;
    }

    /**
     * @return MixedType
     */
    public function getVariableArgType(): MixedType
    {
        return $this->_variable_arg_type;
    }

    /**
     * @return string
     */
    public function getVariableArgName(): string
    {
        return $this->_variable_arg_name;
    }

    /**
     * @return bool
     */
    public function isFuncWithArg(): bool
    {
        return $this->_func_with_arg;
    }

    /**
     * @return string[]
     */
    public function getArgs(): array
    {
        return $this->_args;
    }

    /**
     * @return array
     */
    public function getParamsMap(): array
    {
        return $this->_params_map;
    }

    /**
     * @param bool $func_with_arg
     */
    public function setFuncWithArg(bool $func_with_arg)
    {
        $this->_func_with_arg = $func_with_arg;
    }


}