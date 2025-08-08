<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\VarDumperOutput\CliVarDumpOutput;
use PhpDevCommunity\UniTester\TestCase;

class CliVarDumpOutputTest extends TestCase
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
       $this->testVarDump();
    }

    public function testVarDump()
    {
        ob_start();
        $cliOutput = new CliVarDumpOutput(function ($dumped) {
            echo $dumped;
        });
        $cliOutput->print("Hello, world!");
        $output = ob_get_clean();
        $this->assertStringContains($output, 'string(13) "Hello, world!"');
    }
}
