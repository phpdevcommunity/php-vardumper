<?php

namespace PhpDevCommunity\Debug\Output\BacktraceOutput;

use PhpDevCommunity\Debug\Output\OutputInterface;

final class HtmlOutput implements OutputInterface
{
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
        if (!is_array($value)) {
            return;
        }

        $html[] = '<style>';
        $html[] = file_get_contents(dirname(__DIR__, 3) . '/resources/css/backtrace.css');
        $html[] = '</style>';

        $html[] = '<div class="__beautify-backtrace-container">';
        $html[] = sprintf(
            '<div class="__beautify-backtrace-title">Backtrace — last %d call(s)</div>' . PHP_EOL . PHP_EOL,
            count($value)
        );

        foreach ($value as $i => $entry) {
            $file = $entry['file'] ?? '[internal]';
            $line = $entry['line'] ?? '-';
            $function = ($entry['class'] ?? '') . ($entry['type'] ?? '') . $entry['function'];

            $html[] = sprintf(
                '<div class="__beautify-backtrace-dumper">#%d - %s: Called from %s:%s</div>',
                $i + 1,
                htmlspecialchars($function, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($file, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );
        }

        $html[] = '</div>';

        $outputCallback = $this->output;
        $outputCallback(implode('', $html));
    }
}