<?php

namespace PhpDevCommunity\Debug\Output;

final class CliPrintOutput implements OutputInterface
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
        $dumped = print_r($value, true);
        if ($dumped === false) {
            throw new \RuntimeException('Failed to dump the provided value using print_r.');
        }
        $output = $this->output;
        $output($dumped);
    }

}
