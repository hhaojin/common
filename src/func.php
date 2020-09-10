<?php
/**
 * Created by PhpStorm.
 * User: haojin
 * Date: 2020/9/10
 * Time: 11:19
 */


namespace Timor;


/**
 * 把数组设置成对象的属性
 * @param string | object $obj
 * @param array $data
 * @return array
 */
function mapping($obj, $data)
{
    $arr = [];
    if (is_string($obj)) {
        $obj = new $obj;
    }
    try {
        $properties = (new \ReflectionClass($obj))->getProperties();
        foreach ($properties as $property) {
            $pName = $property->getName();
            $arr[$pName] = isset($data[$pName]) ? $data[$pName] : $property->getValue();
        }
        return $arr;
    } catch (\Exception $e) {
        return $arr;
    }
}

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
