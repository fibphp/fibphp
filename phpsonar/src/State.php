<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 22:38
 */

namespace phpsonar;

use Tiny\Abstracts\AbstractClass;

class State extends AbstractClass
{
    private $_analyzer = null;
    private $_global_map = null;

    private $_error_stack = [];

    public function __construct(Analyzer $analyzer, GlobalMap $global_map = null)
    {
        $global_map = empty($global_map) ? (new GlobalMap()) : $global_map;
        $this->_analyzer = $analyzer;
        $this->_global_map = $global_map;
    }

    /**
     * @return null|GlobalMap
     */
    public function getGlobalMap(): ?GlobalMap
    {
        return $this->_global_map;
    }

    /**
     * @param null|GlobalMap $global_map
     */
    public function setGlobalMap(?GlobalMap $global_map): void
    {
        $this->_global_map = $global_map;
    }

    ############################################################
    ############################################################
    ############################################################

    /**
     * @param $key
     * @param $msg
     */
    public function pushWarn($key, $msg): void
    {
        $this->_error_stack[] = ['warn', $key, $msg];
    }


    /**
     * @return array
     */
    public function getErrorStack(): array
    {
        return $this->_error_stack;
    }

    /**
     * @param $key
     * @param $msg
     */
    public function pushError($key, $msg): void
    {
        $this->_error_stack[] = ['error', $key, $msg];
    }

    public function popError()
    {
        return !empty($this->_error_stack) ? array_pop($this->_error_stack) : [];
    }

    public function walkError(callable $func = null)
    {
        while (!empty($this->_error_stack)) {
            list($tag, $key, $err) = array_pop($this->_error_stack);
            if (!empty($func)) {
                $ret = $func($tag, $key, $err);
                if ($ret === false) {
                    break;
                }
            }
        }
    }

}