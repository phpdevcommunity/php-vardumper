<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\CliPrintOutput;
use PhpDevCommunity\Debug\Output\HtmlOutput;
use PhpDevCommunity\Debug\VarDumper;
use PhpDevCommunity\UniTester\TestCase;

class VarDumperTest extends TestCase
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
        $output = new CliPrintOutput(function ($dumped) {
            $this->assertEquals("foo", $dumped);
        });
        $varDumper = new VarDumper($output);
        $varDumper->dump('foo');
    }
}
