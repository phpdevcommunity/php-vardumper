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
            $this->assertTrue(strip_tags($dumped) !== $dumped);
        };
        $htmlOutput = new HtmlOutput(5, $output);
        $htmlOutput->print(['key' => 'value']);
    }

}
