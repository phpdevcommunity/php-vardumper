<?php

namespace PhpDevCommunity\Debug\Util;

final class ObjectPropertyExtractor
{

    public static function extract(object  $object): array
    {
        $reflection = new \ReflectionClass($object);
        $properties = [];
        $parentClass = $reflection->getParentClass();
        while ($parentClass) {
            foreach ($parentClass->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED) as $property) {
                $property->setAccessible(true);
                $properties[$property->getName()] = $property->getValue($object);
            }
            $parentClass = $parentClass->getParentClass();
        }
        foreach ($reflection->getTraits() as $trait) {
            foreach ($trait->getProperties() as $property) {
                $property = $reflection->getProperty($property->getName());
                $property->setAccessible(true);
                $properties[$property->getName()] = $property->getValue($object);
            }
        }

        foreach (get_object_vars($object) as $name => $value) {
            $properties[$name] = $value;
        }

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->getValue($object);
        }
        $result = [];
        foreach ($properties as $key => $value) {
            $result[$key] = $value;
        }

        if ($reflection->hasMethod('__debugInfo')) {
            $method = $reflection->getMethod('__debugInfo');
            $infos = $method->invoke($object);
            foreach ($infos as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}