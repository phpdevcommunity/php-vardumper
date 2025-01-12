<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\CliPrintOutput;
use PhpDevCommunity\UniTester\TestCase;

class CliPrintOutputTest extends TestCase
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
       $this->testPrint();
    }

    public function testPrint()
    {
        ob_start();
        $cliOutput = new CliPrintOutput(function ($dumped) {
            echo $dumped;
        });
        $cliOutput->print("Hello, world!");
        $output = ob_get_clean();
        $this->assertEquals("Hello, world!", $output);
    }
}