<?php

namespace PhpDevCommunity\Debug\Output\VarDumperOutput;

use PhpDevCommunity\Debug\Output\OutputInterface;
use PhpDevCommunity\Debug\Util\ObjectPropertyExtractor;
use PhpDevCommunity\Debug\Util\DataDescriptor;

final class CliPrintOutput implements OutputInterface
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
                fwrite(STDOUT, $dumped);
            };
        }
        $this->output = $output;
    }

    public function print($value): void
    {
        $description = DataDescriptor::describe($value, 0, $this->maxDepth);
        $fragments = $this->renderValueNode($description);
        $dumped = rtrim(implode('', $fragments), PHP_EOL).PHP_EOL;
        $outputCallback = $this->output;
        $outputCallback($dumped);
    }


    private function renderValueNode($node): array
    {
        $pad = '';
        $indent = $node['depth'];
        $value = $node['value'];
        $type = $node['type'];

        if ($indent) {
            $pad = str_repeat(' ', $indent * 2);
        }
        $out = [];
        if ($node['truncated']) {
            $out[] = $value;
            return $out;
        }

        switch ($type) {
            case 'NULL':
                $out[] = 'null';
                return $out;
            case 'array':
                $out[] = sprintf('%s ['. PHP_EOL, $value);
                foreach ($node['items'] as $k => $description) {
                    $out[] = $pad . "  [$k] => " . implode('', $this->renderValueNode($description)) . PHP_EOL;
                }
                $out[] = $pad . ']';
                return $out;
            case 'object':
                $out[] = sprintf('%s {'. PHP_EOL, $value);
                foreach ($node['properties'] as $k => $description) {
                    $k = sprintf('%s::%s', $node['class'], $k);
                    $out[] = $pad . "  [$k] => " . implode('', $this->renderValueNode($description)) . PHP_EOL;
                }
                $out[] = $pad . "}";
                return $out;
            default:
                $out[] = sprintf('(%s) %s', $type, $value);
                return $out;
        }

    }

}
