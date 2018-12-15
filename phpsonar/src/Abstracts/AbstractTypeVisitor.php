<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\16 0016
 * Time: 0:13
 */

namespace phpsonar\Abstracts;


use phpsonar\Analyzer;
use phpsonar\Interfaces\TypeVisitor;
use Tiny\Abstracts\AbstractClass;

abstract class AbstractTypeVisitor extends AbstractClass implements TypeVisitor
{

    private $_analyzer = null;

    private $_typeVisitorMap = [];

    public function __construct(Analyzer $analyzer, array $visitorMap = [])
    {
        $this->_analyzer = $analyzer;
        $this->_typeVisitorMap = $visitorMap;
    }

    public function registerTypeVisitor($type, TypeVisitor $visitor)
    {
        $this->_typeVisitorMap[$type] = $visitor;
    }

    /**
     * @param $type
     * @return TypeVisitor
     */
    protected function loadTypeVisitor($type)
    {
        $tmpArr = explode('_', $type);
        if (!empty($tmpArr) && !empty($tmpArr[0]) && !empty($this->_typeVisitorMap[$tmpArr[0]])) {
            return $this->_typeVisitorMap[$tmpArr[0]];
        }
        return null;
    }


}