<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\VarDumperOutput\CliPrintOutput;
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
        $cliOutput = new CliPrintOutput(5, function ($dumped) {
            echo $dumped;
        });
        $cliOutput->print("Hello, world!");
        $output = ob_get_clean();
        $this->assertEquals('(string) "Hello, world!"'.PHP_EOL, $output);
    }
}