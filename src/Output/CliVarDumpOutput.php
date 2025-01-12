<?php

namespace PhpDevCommunity\Debug\Output;

final class CliVarDumpOutput implements OutputInterface
{

    /**
     * @var callable|null
     */
    private $output;

    public function __construct(callable $output = null)
    {
        if ($output === null) {
            $output = function (string $dumped) {
                fwrite(STDOUT, $dumped);
            };
        }
        $this->output = $output;
    }

    public function print($value): void
    {
        ob_start();
        var_dump($value);
        $dumped = ob_get_clean();
        if ($dumped === false) {
            throw new \RuntimeException('Failed to dump the provided value using var_dump.');
        }
        $output = $this->output;
        $output($dumped);
    }
}
