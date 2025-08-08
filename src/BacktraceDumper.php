<?php

namespace PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\BacktraceOutput\CliOutput;
use PhpDevCommunity\Debug\Output\BacktraceOutput\HtmlOutput;
use PhpDevCommunity\Debug\Output\OutputInterface;

final class BacktraceDumper
{

    private OutputInterface $output;

    public function __construct(OutputInterface $output = null)
    {
        if ($output === null) {
            $output = \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? new CliOutput() : new HtmlOutput();
        }
        $this->output = $output;
    }

    public function dump(int $backtraceLimit = 10, int $offset = 1, array $traces = []): void
    {
        if ($backtraceLimit <= 0) {
            $backtraceLimit = 1;
        }

        if ($offset <= 0) {
            $offset = 0;
        }

        if (empty($traces)) {
            $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $backtraceLimit);
        }
        $traces = array_slice($traces, $offset, $backtraceLimit, false);
        $this->output->print(array_reverse($traces));
    }

}