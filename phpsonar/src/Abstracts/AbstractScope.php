<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2019/4/10 0010
 * Time: 16:35
 */

namespace phpsonar\Abstracts;


use phpsonar\CodeAt;
use phpsonar\Exception\ReDefineClassWarn;
use phpsonar\Exception\ReDefineConstWarn;
use phpsonar\Exception\ReDefineFuncWarn;
use phpsonar\Exception\ReDefineInterfaceWarn;
use phpsonar\Exception\ReDefineVarWarn;
use phpsonar\State;
use phpsonar\Util;
use Tiny\Abstracts\AbstractClass;

class AbstractScope extends AbstractClass
{
    protected $_const_map = [];
    protected $_function_map = [];
    protected $_class_map = [];
    protected $_interface_map = [];
    protected $_var_map = [];
    protected $_code_at_map = [];

    /**
     * @param $key
     * @return null| State
     */
    public function tryGetCodeAt($key)
    {
        return Util::v($this->_code_at_map, $key, null);
    }

    protected static function _fixName($name)
    {
        if (substr($name, 0, 1) == '"' && substr($name, -1, 1) == '"') {
            $name = substr($name, 1, -1);
            return str_replace("\\\\", "\\", $name);
        }
        return $name;
    }

    #####################################################################
    ############################    Const    ############################
    #####################################################################


    /**
     * @return array
     */
    public function getConstMap(): array
    {
        return $this->_const_map;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasConst(string $name): bool
    {
        return isset($this->_const_map[$name]);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getConst(string $name)
    {
        return isset($this->_const_map[$name]) ? $this->_const_map[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param CodeAt|null $codeAt
     * @throws ReDefineConstWarn
     */
    public function setConst(string $name, $value, CodeAt $codeAt = null): void
    {
        $name = self::_fixName($name);
        if (isset($this->_const_map[$name])) {
            $this->_const_map[$name] = $value;
            throw new ReDefineConstWarn("const {$name} Already defined", null, $codeAt, $name);
        } else {
            $this->_const_map[$name] = $value;
            $this->_code_at_map[$name] = $codeAt;
        }
    }

    ####################################################################
    ############################  Function  ############################
    ####################################################################

    /**
     * @param string $name
     * @return bool
     */
    public function hasFunction(string $name): bool
    {
        return isset($this->_function_map[$name]);
    }

    /**
     * @return array
     */
    public function getFunctionMap(): array
    {
        return $this->_function_map;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getFunction(string $name)
    {
        return isset($this->_function_map[$name]) ? $this->_function_map[$name] : null;
    }

    /**
     * @param $name
     * @param $func
     * @param CodeAt|null $codeAt
     * @throws ReDefineFuncWarn
     */
    public function setFunction($name, $func, CodeAt $codeAt = null): void
    {
        $name = self::_fixName($name);
        if (isset($this->_function_map[$name])) {
            $this->_function_map[$name] = $func;
            throw new ReDefineFuncWarn("func {$name} Already defined", null, $codeAt, $name);
        } else {
            $this->_function_map[$name] = $func;
            $this->_code_at_map[$name] = $codeAt;
        }
    }

    #####################################################################
    ############################    Class    ############################
    #####################################################################

    /**
     * @param string $name
     * @return bool
     */
    public function hasClass(string $name): bool
    {
        return isset($this->_class_map[$name]);
    }

    /**
     * @return array
     */
    public function getClassMap(): array
    {
        return $this->_class_map;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getClass(string $name)
    {
        return isset($this->_class_map[$name]) ? $this->_class_map[$name] : null;
    }

    /**
     * @param $name
     * @param $cls
     * @param CodeAt|null $codeAt
     * @throws ReDefineClassWarn
     */
    public function setClass($name, $cls, CodeAt $codeAt = null): void
    {
        $name = self::_fixName($name);
        if (isset($this->_class_map[$name])) {
            $this->_class_map[$name] = $cls;
            throw new ReDefineClassWarn("class {$name} Already defined", null, $codeAt, $name);
        } else {
            $this->_class_map[$name] = $cls;
            $this->_code_at_map[$name] = $codeAt;
        }
    }


    #####################################################################
    ############################  Interface  ############################
    #####################################################################

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return isset($this->_interface_map[$name]);
    }

    /**
     * @return array
     */
    public function getInterfaceMap(): array
    {
        return $this->_interface_map;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getInterface(string $name)
    {
        return isset($this->_interface_map[$name]) ? $this->_interface_map[$name] : null;
    }


    /**
     * @param $name
     * @param $interface
     * @param CodeAt|null $codeAt
     * @throws ReDefineInterfaceWarn
     */
    public function setInterface($name, $interface, CodeAt $codeAt = null): void
    {
        $name = self::_fixName($name);
        if (isset($this->_interface_map[$name])) {
            $this->_interface_map[$name] = $interface;
            throw new ReDefineInterfaceWarn("interface {$name} Already defined", null, $codeAt, $name);
        } else {
            $this->_interface_map[$name] = $interface;
            $this->_code_at_map[$name] = $codeAt;
        }
    }


    #####################################################################
    ###############################  Var  ###############################
    #####################################################################

    /**
     * @param string $name
     * @return bool
     */
    public function hasVar(string $name): bool
    {
        return isset($this->_var_map[$name]);
    }

    /**
     * @return array
     */
    public function getVarMap(): array
    {
        return $this->_var_map;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getVar(string $name)
    {
        return isset($this->_var_map[$name]) ? $this->_var_map[$name] : null;
    }

    /**
     * @param $name
     * @param $var
     * @param CodeAt|null $codeAt
     * @throws ReDefineVarWarn
     */
    public function setVar($name, $var, CodeAt $codeAt = null): void
    {
        $name = self::_fixName($name);
        if (isset($this->_var_map[$name])) {
            $this->_var_map[$name] = $var;
            throw new ReDefineVarWarn("var {$name} Already defined", null, $codeAt, $name);
        } else {
            $this->_var_map[$name] = $var;
            $this->_code_at_map[$name] = $codeAt;
        }
    }


}