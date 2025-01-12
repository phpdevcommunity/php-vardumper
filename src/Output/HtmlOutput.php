<?php

namespace PhpDevCommunity\Debug\Output;

use ReflectionClass;
use ReflectionProperty;

final class HtmlOutput implements OutputInterface
{

    private int $maxDepth;

    public function __construct(int $maxDepth = 5)
    {
        $this->maxDepth = $maxDepth;
    }

    public function print($value): void
    {
        $html[] = '<style>';
        $html[] = file_get_contents(dirname(__DIR__, 2) . '/resources/css/dump.css');
        $html[] = '</style>';
        $html[] = '<div class="beautify-print">';
        $result = $this->inspectItem($value);
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($result));
        foreach ($it as $v) {
            $html[] = $v;
        }
        $html[] = '</div>';
        echo implode(PHP_EOL, $html);
    }

    private function inspectItem($item, int $indent = 0): array
    {
        $indentStr = str_repeat('&nbsp;', $indent * 3);
        $html = [];
        if ($indent >= $this->maxDepth) {
            $html[] = "<div>{$indentStr}...</div>";
            return $html;
        }

        if (is_array($item)) {
            $html[] = "<span class='type'>array</span> <small><i>(Size: " . count($item) . ")</i></small> (<br>";
            foreach ($item as $key => $value) {
                $html[] = "{$indentStr}<span class='key'>$key</span> => ";
                $html[] = $this->inspectItem($value, $indent + 1);
            }
            $html[] = "{$indentStr})";
        } elseif (is_object($item)) {
            $html[] =  "<span class='type'>object</span> (" . get_class($item) . ") {<br>";
            $properties = $this->inspectObject($item);
            foreach ($properties as $key => $value) {
                $html[] = "{$indentStr}<span class='key'>$key</span> => ";
                $html[] = $this->inspectItem($value, $indent + 1);
            }
            $html[] = "{$indentStr}}";
        } elseif (is_string($item)) {
            $html[] = "<span class='string'><span class='type'>string</span> '$item'</span> <small><i>(Lenght: " . strlen($item) . ")</i></small>";
        } elseif (is_int($item)) {
            $html[] = "<span class='number'><span class='type'>int</span> $item</span>";
        } elseif (is_float($item)) {
            $html[] = "<span class='number'><span class='type'>float</span> $item</span>";
        } elseif (is_bool($item)) {
            $html[] = "<span class='boolean'><span class='type'>boolean</span> " . ($item ? 'true' : 'false') . "</span>";
        } elseif (is_null($item)) {
            $html[] = "<span class='null'>null</span>";
        } else {
            $html[] = "<span class='type'>" . gettype($item) . "</span> " . $item;
        }

        $html[] = "<br>";
        return $html;

    }

    private function inspectObject(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $properties = [];
        $parentClass = $reflection->getParentClass();
        while ($parentClass) {
            foreach ($parentClass->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED) as $property) {
                $property->setAccessible(true);
                $properties[$property->getName()] = $property->getValue($object);
            }
            $parentClass = $parentClass->getParentClass();
        }
        foreach ($reflection->getTraits(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED) as $trait) {
            foreach ($trait->getProperties() as $property) {
                $property = $reflection->getProperty($property->getName());
                $property->setAccessible(true);
                $properties[$property->getName()] = $property->getValue($object);
            }
        }
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $properties[$property->getName()] = $property->getValue($object);
        }
        $result = [];
        foreach ($properties as $key => $value) {
            $result[$key] = $value;
        }

        return $result;

    }
}
