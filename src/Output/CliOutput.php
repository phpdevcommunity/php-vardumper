<?php

namespace PhpDevCommunity\Debug\Output;

final class CliOutput implements OutputInterface
{
    public function print($value): void
    {
        $output = print_r($value, true);
        if ($output === false) {
            throw new \RuntimeException('Failed to print ');
        }
        echo $output;
    }
}
