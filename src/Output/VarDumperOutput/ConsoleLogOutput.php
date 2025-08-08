<?php

namespace PhpDevCommunity\Debug\Output\VarDumperOutput;

use PhpDevCommunity\Debug\Output\OutputInterface;

final class ConsoleLogOutput implements OutputInterface
{
    /**
     * @var callable|null
     */
    private $output;

    public function __construct(callable $output = null)
    {
        if ($output === null) {
            $output = function (string $dumped) {
                echo $dumped;
            };
        }
        $this->output = $output;
    }

    public function print($value): void
    {
        if ($this->isCli()) {
            (new CliPrintOutput())->print($value);
            return;
        }
        $html[] = '<script>';
        $js = file_get_contents(dirname(__DIR__, 3) . '/resources/js/console.log.js');
        $html[] = str_replace('[value_to_debug]', json_encode(print_r($value, true)), $js);
        $html[] = '</script>';

        $dumped = implode('', $html);
        $output = $this->output;
        $output($dumped);
    }

    private function isCli(): bool
    {
        return \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true);
    }
}
