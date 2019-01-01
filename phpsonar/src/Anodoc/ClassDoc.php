<?php

namespace phpsonar\Anodoc;

use phpsonar\Anodoc\ClassDoc\InvalidAttributeDoc;
use phpsonar\Anodoc\ClassDoc\InvalidMethodDoc;

class ClassDoc
{

    private $className = '';
    private $classDoc = '';
    private $methodDocs = [];
    private $attributeDocs = [];

    /**
     * ClassDoc constructor.
     * @param $className
     * @param DocComment $classDoc
     * @param array $methodDocs
     * @param array $attributeDocs
     * @throws InvalidAttributeDoc
     * @throws InvalidMethodDoc
     */
    public function __construct($className, DocComment $classDoc, array $methodDocs = [], array $attributeDocs = [])
    {
        $this->className = $className;
        $this->classDoc = $classDoc;
        foreach ($methodDocs as $methodName => $docComment) {
            $this->setMethodDoc($methodName, $docComment);
        }
        foreach ($attributeDocs as $attribute => $docComment) {
            $this->setAttributeDoc($attribute, $docComment);
        }
    }

    /**
     * @param $methodName
     * @param $docComment
     * @throws InvalidMethodDoc
     */
    private function setMethodDoc($methodName, $docComment)
    {
        if ($docComment instanceof DocComment) {
            $this->methodDocs[$methodName] = $docComment;
        } else {
            throw new InvalidMethodDoc("'$methodName' is not a valid method doc.");
        }
    }

    /**
     * @param $attribute
     * @param $docComment
     * @throws InvalidAttributeDoc
     */
    private function setAttributeDoc($attribute, $docComment)
    {
        if ($docComment instanceof DocComment) {
            $this->attributeDocs[$attribute] = $docComment;
        } else {
            throw new InvalidAttributeDoc("'$attribute' is not a valid attribute doc.");
        }
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getMainDoc()
    {
        return $this->classDoc;
    }

    public function getMethodDoc($method)
    {
        return $this->getItemDoc($this->methodDocs, $method);
    }

    public function getAttributeDoc($attribute)
    {
        return $this->getItemDoc($this->attributeDocs, $attribute);
    }

    private function getItemDoc($collection, $key)
    {
        if (isset($collection[$key])) {
            return $collection[$key];
        }
        return new NullDocComment();
    }

}