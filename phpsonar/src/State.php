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

    private $_warn_map = [];
    private $_error_map = [];

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
     * @return array
     */
    public function getWarnMap(): array
    {
        return $this->_warn_map;
    }

    /**
     * @param $key
     * @param $msg
     */
    public function addWarn($key, $msg): void
    {
        $this->_warn_map[$key] = $msg;
    }

    /**
     * @return array
     */
    public function getErrorMap(): array
    {
        return $this->_error_map;
    }

    /**
     * @param $key
     * @param $msg
     */
    public function addError($key, $msg): void
    {
        $this->_error_map[$key] = $msg;
    }


}