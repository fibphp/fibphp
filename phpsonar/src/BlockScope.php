<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2019/4/10 0010
 * Time: 16:33
 */

namespace phpsonar;


use phpsonar\Abstracts\AbstractScope;

class BlockScope extends GlobalScope
{

    private $_pre_scope = null;

    public function __construct(AbstractScope $pre_scope)
    {
        $this->_pre_scope = $pre_scope;
    }

    /**
     * @return null|AbstractScope
     */
    public function getPreScope():?AbstractScope
    {
        return $this->_pre_scope;
    }

    /**
     * @param null|AbstractScope $pre_scope
     */
    public function setPreScope(?AbstractScope $pre_scope): void
    {
        $this->_pre_scope = $pre_scope;
    }

}