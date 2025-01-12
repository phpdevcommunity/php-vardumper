<?php

namespace PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\CliOutput;
use PhpDevCommunity\Debug\Output\HtmlOutput;
use PhpDevCommunity\Debug\Output\OutputInterface;
use ReflectionClass;
use ReflectionProperty;

final class VarDumper
{
    private OutputInterface $output;

    public function __construct(OutputInterface $output = null)
    {
        if ($output === null) {
            $output = \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? new CliOutput() : new HtmlOutput();
        }
        $this->output = $output;
    }

    public function dump(...$vars): void
    {
        foreach ($vars as $item) {
            echo $this->output->print($item);
        }
    }

}
