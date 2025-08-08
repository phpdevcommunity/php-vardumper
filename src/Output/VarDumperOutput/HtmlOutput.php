<?php

namespace PhpDevCommunity\Debug\Output\VarDumperOutput;

use PhpDevCommunity\Debug\Output\OutputInterface;
use PhpDevCommunity\Debug\Util\ObjectPropertyExtractor;
use PhpDevCommunity\Debug\Util\DataDescriptor;
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
        if ($maxDepth <= 0) {
            $maxDepth = 5;
        }
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
        $css  = file_get_contents(dirname(__DIR__, 3) . '/resources/css/dump.css');
        $html[] = str_replace('#uniqId', "#$id", $css);
        $html[] = '</style>';

        $html[] = '<script>';
        $js = file_get_contents(dirname(__DIR__, 3) . '/resources/js/debug.js');
        $js = str_replace('#uniqId', "#$id", $js);
        $html[] = $js;
        $html[] = '</script>';

        $html[] = sprintf('<div id="%s" class="__beautify-var-dumper">', $id);

        $description = DataDescriptor::describe($value, 0, $this->maxDepth);
        $fragments = $this->renderValueNode($description);
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($fragments));
        foreach ($it as $v) {
            $html[] = $v;
        }
        $html[] = '</div>';
        $dumped = implode('', $html);

        $outputCallback = $this->output;
        $outputCallback($dumped);
    }

    private function renderValueNode($node): array
    {
        $html = [];
        $indent = $node['depth'];
        $indentStr = str_repeat('&nbsp;', $indent * 2);
        if ($node['truncated']) {
            $html[] = "<div>{$indentStr}{$node['value']}</div>";
            return $html;
        }

        $value = $node['value'];
        $type = $node['type'];
        switch ($type) {
            case 'NULL':
                $length = $node['length'] ?? null;
                $html[] = "<span class='$type'>$value</span>";
                break;
            case 'string':
            case 'int':
            case 'float':
            case 'bool':
            case 'boolean':
                $length = $node['length'] ?? null;
                $html[] = "<span class='$type'><span class='type'>$type</span> $value</span>";
                if ($length) {
                    $html[] = " <small><i>(Lenght: $length)</i></small>";
                }
                break;
            case 'array':
                $id = 'target_'.md5(uniqid());
                $count = $node['count'];
                $caretValue =  sprintf(
                    "data-target='#%s'>array</span> <small><i>(Size: %d)</i></small> (<br>",
                    $id,
                    $count
                );
                if ($indent <= 1) {
                    $html[] = "<span class='type caret caret-down' $caretValue";
                    $html[] = "<div class='nested active' id='{$id}'>";
                }else {
                    $html[] = "<span class='type caret' $caretValue";
                    $html[] = "<div class='nested' id='{$id}'>";
                }
                foreach ($node['items'] as $key => $description) {
                    if (is_string($key)) {
                        $key = sprintf('"%s"', $key);
                    }
                    $html[] = "{$indentStr}  <span class='key'>$key</span> => ";
                    $html[] = $this->renderValueNode($description);
                }
                $html[] = "{$indentStr})";
                $html[] = "</div>";
                return $html;
            case 'object':
                $html[] =  "<span class='type'>object</span> $value {<br>";
                foreach ($node['properties'] as $key => $description) {
                    $key = sprintf('%s::%s', $node['class'], $key);
                    $html[] = "{$indentStr}  <span class='key'>$key</span> => ";
                    $html[] =  $this->renderValueNode($description);
                }
                $html[] = "{$indentStr}}";
                break;
            default:
                $html[] = "<span class='$type'><span class='type'>$type</span> $value</span>";
        }

        $html[] = "<br>";
        return $html;
    }

}
