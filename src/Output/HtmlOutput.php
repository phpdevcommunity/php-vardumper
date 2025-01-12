<?php

namespace PhpDevCommunity\Debug\Output;

use ReflectionClass;
use ReflectionProperty;

final class HtmlOutput implements OutputInterface
{

    private array $styles = [];

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
        if ($this->styles === []) {
            $this->styles[] = '<style>';
            $css  = file_get_contents(dirname(__DIR__, 2) . '/resources/css/dump.css');
            $this->styles[] = $css;
            $this->styles[] = '</style>';
            $html = $this->styles;
        }
        $html[] = '<div class="__beautify-var-dumper">';
        $result = $this->inspectItem($value);
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($result));
        foreach ($it as $v) {
            $html[] = $v;
        }
        $html[] = '</div>';
        $dumped = implode(PHP_EOL, $html);

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
        foreach ($reflection->getTraits() as $trait) {
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
