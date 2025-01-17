<?php

namespace PhpDevCommunity\Debug\Output;

use ReflectionClass;
use ReflectionProperty;

final class HtmlOutput implements OutputInterface
{

    private int $maxDepth;
    /**
     * @var callable|null
     */
    private $output;

    public function __construct(int $maxDepth = 5, callable $output = null)
    {
        $this->maxDepth = $maxDepth;
        if ($output === null) {
            $output = function (string $dumped) {
                echo $dumped;
            };
        }
        $this->output = $output;
    }

    public function print($value): void
    {
        $id ='var_dump_'.md5(uniqid());

        $html[] = '<style>';
        $css  = file_get_contents(dirname(__DIR__, 2) . '/resources/css/dump.css');
        $html[] = str_replace('#uniqId', "#$id", $css);
        $html[] = '</style>';

        $html[] = '<script>';
        $js = file_get_contents(dirname(__DIR__, 2) . '/resources/js/debug.js');
        $js = str_replace('#uniqId', "#$id", $js);
        $html[] = $js;
        $html[] = '</script>';

        $html[] = sprintf('<div id="%s" class="__beautify-var-dumper">', $id);
        $result = $this->inspectItem($value);
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($result));
        foreach ($it as $v) {
            $html[] = $v;
        }
        $html[] = '</div>';
        $dumped = implode('', $html);

        $output = $this->output;
        $output($dumped);
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
            $id = 'target_'.md5(uniqid());
            $caretValue =  "data-target='#{$id}'>array</span> <small><i>(Size: " . count($item) . ")</i></small> (<br>";
            if ($indent <= 1) {
                $html[] = "<span class='type caret caret-down' $caretValue";
                $html[] = "<div class='nested active' id='{$id}'>";
            }else {
                $html[] = "<span class='type caret' $caretValue";
                $html[] = "<div class='nested' id='{$id}'>";
            }
            foreach ($item as $key => $value) {
                $html[] = "{$indentStr}<span class='key'>$key</span> => ";
                $html[] = $this->inspectItem($value, $indent + 1);
            }
            $html[] = "{$indentStr})";
            $html[] = "</div>";
            return $html;
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
