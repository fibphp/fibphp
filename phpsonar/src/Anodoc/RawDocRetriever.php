<?php

namespace phpsonar\Anodoc;

class RawDocRetriever
{

    private $classDoc = '';
    private $attrDocs = '';
    private $methodDocs = '';

    /**
     * RawDocRetriever constructor.
     * @param $className
     * @throws \ReflectionException
     */
    public function __construct($className)
    {
        $reflection = new \ReflectionClass($className);
        $this->classDoc = $reflection->getDocComment();
        $this->attrDocs = $this->getAttrDocs($reflection);
        $this->methodDocs = $this->getMethodDocs($reflection);
        unset($reflection);
    }

    public function rawClassDoc()
    {
        return $this->classDoc;
    }

    public function rawAttrDocs()
    {
        return $this->attrDocs;
    }

    public function rawMethodDocs()
    {
        return $this->methodDocs;
    }

    private function getAttrDocs($reflection)
    {
        /** @var \ReflectionClass $reflection */
        $properties = $reflection->getProperties();
        $docs = [];
        foreach ($properties as $property) {
            $docs[$property->getName()] = preg_replace(
                "/\n\s\s+/", "\n ", $property->getDocComment()
            );
        }
        return $docs;
    }

    private function getMethodDocs($reflection)
    {
        /** @var \ReflectionClass $reflection */
        $methods = $reflection->getMethods();
        $docs = [];
        foreach ($methods as $method) {
            $docs[$method->getName()] = preg_replace(
                "/\n\s\s+/", "\n ", $method->getDocComment()
            );
        }
        return $docs;
    }

}