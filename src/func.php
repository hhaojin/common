<?php
/**
 * Created by PhpStorm.
 * User: haojin
 * Date: 2020/9/10
 * Time: 11:19
 */


namespace Timor;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Timor\Annotation\Mapping;

/**
 * 把数组设置成对象的属性
 * @param string | object $obj
 * @param array $data
 * @return array
 */
if (!function_exists("mapping")) {
    function mapping($obj, $data)
    {
        $arr = [];
        if (is_string($obj)) {
            $obj = new $obj;
        }
        loadAnnotaionFiles();
        $reader = new AnnotationReader();
        try {
            $properties = (new \ReflectionClass($obj))->getProperties();
            foreach ($properties as $property) {
                $keyName = $pName = $property->getName();
                /** @var Mapping $annotation */
                $annotation = $reader->getPropertyAnnotation($property, Mapping::class);
                if ($annotation) {
                    $keyName = $annotation->key ?: $pName;
                }
                if (!$property->isPublic()) {
                    $property->setAccessible(true);
                }
                $arr[$keyName] = isset($data[$pName]) ? $data[$pName] : $property->getValue($obj);
            }
            return $arr;
        } catch (\Exception $e) {
            return $arr;
        }
    }
}

if (!function_exists("loadAnnotaionFiles")) {
    function loadAnnotaionFiles()
    {
        $annotationFiles = glob(dirname(__DIR__) . "/src/Annotation/*.php");
        if (is_array($annotationFiles)) {
            foreach ($annotationFiles as $annotationFile) {
                AnnotationRegistry::registerFile($annotationFile);
            }
        }
    }
}

if (!function_exists("jsonForObj")) {
    function jsonForObj(string $class, $params)
    {
        try {
            $refObj = new \ReflectionClass($class);
            $instance = $refObj->newInstance();
            $pubMethods = $refObj->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($pubMethods as $pubMethod) {
                if (preg_match("/^set(\w+)/", $pubMethod->getName(), $matches)) {
                    $fliterName = strtolower(preg_replace("/(?<=[a-z])([A-Z])/", "_$1", $matches[1]));
                    $props = $refObj->getProperties();
                    foreach ($props as $key => $prop) {
                        if ($prop->getName() === lcfirst($matches[1])) {//productId
                            try {
                                $method = $refObj->getMethod('set' . $matches[1]);
                            } catch (\ReflectionException $e) {
                                throw new \ReflectionException($e->getMessage());
                            }
                            $args = $method->getParameters();
                            if (count($args) == 1 && isset($params[$fliterName])) {
                                $method->invoke($instance, $params[$fliterName]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {

        }

        return $instance;
    }
}
