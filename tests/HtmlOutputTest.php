<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\HtmlOutput;
use PhpDevCommunity\UniTester\TestCase;

class HtmlOutputTest extends TestCase
{

    protected function setUp(): void
    {
        // TODO: Implement setUp() method.
    }

    protected function tearDown(): void
    {
        // TODO: Implement tearDown() method.
    }

    protected function execute(): void
    {
        $this->testInspectItem();
    }

    public function testInspectItem(): void
    {
        $output = function (string $dumped) {
            $dumped = str_replace(PHP_EOL, '', $dumped);
            $this->assertStringEndsWith(
                $dumped,
                <<<HTML
<div class="__beautify-var-dumper"><span class='type'>array</span> <small><i>(Size: 1)</i></small> (<br><span class='key'>key</span> => <span class='string'><span class='type'>string</span> 'value'</span> <small><i>(Lenght: 5)</i></small><br>)<br></div>
HTML
            );
        };
        $htmlOutput = new HtmlOutput(5, $output);
        $htmlOutput->print(['key' => 'value']);
    }

}