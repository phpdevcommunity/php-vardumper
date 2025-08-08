<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Output\VarDumperOutput\CliPrintOutput;
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
        $output = new CliPrintOutput(5, function ($dumped) {
            $this->assertEquals('(string) "foo"'.PHP_EOL, $dumped);
        });
        $varDumper = new VarDumper($output);
        $varDumper->dump('foo');
    }
}
