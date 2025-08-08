<?php

namespace PhpDevCommunity\Debug\Output\BacktraceOutput;

use PhpDevCommunity\Debug\Output\OutputInterface;

final class CliOutput implements OutputInterface
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
        if (!is_array($value)) {
            return;
        }

        $out[] = sprintf("Backtrace (last %d calls):".PHP_EOL.PHP_EOL, count($value));

        $maxWidth = $this->geTerminalWidth() - 12;
        foreach ($value as $i => $entry) {
            $file = $entry['file'] ?? '[internal]';
            $line = $entry['line'] ?? '-';
            $function = ($entry['class'] ?? '') . ($entry['type'] ?? '') . $entry['function'];

            $file = self::truncateLeft($file, $maxWidth);
            $function = self::truncateLeft($function, $maxWidth);

            $out[]  = sprintf("#%d", $i + 1);
            $out[]  = sprintf("  File    : %s:%s", $file, $line);
            $out[]  = "  Call    : $function";
        }

        $outputCallback = $this->output;
        $outputCallback(implode(PHP_EOL, $out).PHP_EOL);
    }

    private static function truncateLeft(string $text, int $maxLength): string {
        return strlen($text) > $maxLength
            ? '...' . substr($text, -($maxLength - 3))
            : $text;
    }

    private function geTerminalWidth(): int
    {
        return ((int)exec('tput cols') ?? 85 - 5);
    }
}