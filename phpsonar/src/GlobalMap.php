<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\18 0018
 * Time: 23:59
 */

namespace phpsonar;


use phpsonar\Exception\ReDefineClassWarn;
use phpsonar\Exception\ReDefineConstWarn;
use phpsonar\Exception\ReDefineFuncWarn;
use phpsonar\Exception\ReDefineInterfaceWarn;
use phpsonar\Exception\ReDefineVarWarn;
use Tiny\Abstracts\AbstractClass;

class GlobalMap extends AbstractClass
{

    private $_const_map = [];
    private $_function_map = [];
    private $_class_map = [];
    private $_interface_map = [];
    private $_var_map = [];
    private $_code_at_map = [];

    #####################################################################
    ############################    Const    ############################
    #####################################################################

    private static function _fixName($name)
    {
        if (substr($name, 0, 1) == '"' && substr($name, -1, 1) == '"') {
            $name = substr($name, 1, -1);
            return str_replace("\\\\", "\\", $name);
        }
        return $name;
    }

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
            $this->_code_at_map[$name] = $codeAt;
            throw new ReDefineConstWarn("const {$name} Already defined", null, $codeAt);
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
            $this->_code_at_map[$name] = $codeAt;
            throw new ReDefineFuncWarn("func {$name} Already defined", null, $codeAt);
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
            $this->_code_at_map[$name] = $codeAt;
            throw new ReDefineClassWarn("class {$name} Already defined", null, $codeAt);
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
            $this->_code_at_map[$name] = $codeAt;
            throw new ReDefineInterfaceWarn("interface {$name} Already defined", null, $codeAt);
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
            $this->_code_at_map[$name] = $codeAt;
            throw new ReDefineVarWarn("var {$name} Already defined", null, $codeAt);
        } else {
            $this->_var_map[$name] = $var;
            $this->_code_at_map[$name] = $codeAt;
        }
    }

}