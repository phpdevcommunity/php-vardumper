<?php

namespace PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\OutputInterface;
use PhpDevCommunity\Debug\Output\VarDumperOutput\CliPrintOutput;
use PhpDevCommunity\Debug\Output\VarDumperOutput\HtmlOutput;

final class VarDumper
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output = null)
    {
        if ($output === null) {
            $output = \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? new CliPrintOutput() : new HtmlOutput();
        }
        $this->output = $output;
    }

    public function dump(...$vars): void
    {
        foreach ($vars as $item) {
           $this->output->print($item);
        }
    }
}
