<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\18 0018
 * Time: 23:59
 */

namespace phpsonar;


use phpsonar\Exception\ParserWarn;
use Tiny\Abstracts\AbstractClass;

class GlobalMap extends AbstractClass
{

    private $_const_map = [];
    private $_function_map = [];
    private $_class_map = [];
    private $_interface_map = [];
    private $_var_map = [];

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
     * @throws ParserWarn
     */
    public function setConst(string $name, $value): void
    {
        $name = self::_fixName($name);
        if (isset($this->_const_map[$name])) {
            $this->_const_map[$name] = $value;
            throw new ParserWarn("const {$name} Already defined");
        } else {
            $this->_const_map[$name] = $value;
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
     * @throws ParserWarn
     */
    public function setFunction($name, $func): void
    {
        $name = self::_fixName($name);
        if (isset($this->_function_map[$name])) {
            $this->_function_map[$name] = $func;
            throw new ParserWarn("func {$name} Already defined");
        } else {
            $this->_function_map[$name] = $func;
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
     * @throws ParserWarn
     */
    public function setClass($name, $cls): void
    {
        $name = self::_fixName($name);
        if (isset($this->_class_map[$name])) {
            $this->_class_map[$name] = $cls;
            throw new ParserWarn("class {$name} Already defined");
        } else {
            $this->_class_map[$name] = $cls;
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
     * @throws ParserWarn
     */
    public function setInterface($name, $interface): void
    {
        $name = self::_fixName($name);
        if (isset($this->_interface_map[$name])) {
            $this->_interface_map[$name] = $interface;
            throw new ParserWarn("interface {$name} Already defined");
        } else {
            $this->_interface_map[$name] = $interface;
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
     * @throws ParserWarn
     */
    public function setVar($name, $var): void
    {
        $name = self::_fixName($name);
        if (isset($this->_var_map[$name])) {
            $this->_var_map[$name] = $var;
            throw new ParserWarn("var {$name} Already defined");
        } else {
            $this->_var_map[$name] = $var;
        }
    }

}