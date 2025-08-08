<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\BacktraceDumper;
use PhpDevCommunity\Debug\Output\BacktraceOutput\CliOutput;
use PhpDevCommunity\Debug\VarDumper;
use PhpDevCommunity\UniTester\TestCase;

class BacktraceDumperTest extends TestCase
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
        $fakeTrace = [
            [
                'file' => '/var/www/app/src/Controller/HomeController.php',
                'line' => 42,
                'function' => 'indexAction',
                'class' => 'App\\Controller\\HomeController',
                'object' => (object) [],
                'type' => '->',
                'args' => [
                    ['type' => 'string', 'value' => 'Hello world'],
                    ['type' => 'int', 'value' => 123],
                ],
            ],
            [
                'file' => '/var/www/app/vendor/symfony/http-kernel/HttpKernel.php',
                'line' => 158,
                'function' => 'handleRaw',
                'class' => 'Symfony\\Component\\HttpKernel\\HttpKernel',
                'object' => (object) [],
                'type' => '->',
                'args' => [],
            ],
            [
                'file' => '/var/www/app/vendor/symfony/http-kernel/HttpKernel.php',
                'line' => 80,
                'function' => 'handle',
                'class' => 'Symfony\\Component\\HttpKernel\\HttpKernel',
                'object' => (object) [],
                'type' => '->',
                'args' => [
                    ['type' => 'string', 'value' => 'prod'],
                    ['type' => 'bool', 'value' => true],
                ],
            ],
            [
                'file' => '/var/www/app/public/index.php',
                'line' => 25,
                'function' => '{closure}',
                'args' => [],
            ],
            [
                'file' => '/var/www/app/public/index.php',
                'line' => 15,
                'function' => 'require_once',
                'args' => ['/var/www/app/config/bootstrap.php'],
            ],
        ];

        $output = new CliOutput(function ($dumped) use($fakeTrace) {
            $this->assertEquals(base64_encode($dumped), 'QmFja3RyYWNlIChsYXN0IDUgY2FsbHMpOgoKCiMxCiAgRmlsZSAgICA6IC92YXIvd3d3L2FwcC9wdWJsaWMvaW5kZXgucGhwOjE1CiAgQ2FsbCAgICA6IHJlcXVpcmVfb25jZQojMgogIEZpbGUgICAgOiAvdmFyL3d3dy9hcHAvcHVibGljL2luZGV4LnBocDoyNQogIENhbGwgICAgOiB7Y2xvc3VyZX0KIzMKICBGaWxlICAgIDogL3Zhci93d3cvYXBwL3ZlbmRvci9zeW1mb255L2h0dHAta2VybmVsL0h0dHBLZXJuZWwucGhwOjgwCiAgQ2FsbCAgICA6IFN5bWZvbnlcQ29tcG9uZW50XEh0dHBLZXJuZWxcSHR0cEtlcm5lbC0+aGFuZGxlCiM0CiAgRmlsZSAgICA6IC92YXIvd3d3L2FwcC92ZW5kb3Ivc3ltZm9ueS9odHRwLWtlcm5lbC9IdHRwS2VybmVsLnBocDoxNTgKICBDYWxsICAgIDogU3ltZm9ueVxDb21wb25lbnRcSHR0cEtlcm5lbFxIdHRwS2VybmVsLT5oYW5kbGVSYXcKIzUKICBGaWxlICAgIDogL3Zhci93d3cvYXBwL3NyYy9Db250cm9sbGVyL0hvbWVDb250cm9sbGVyLnBocDo0MgogIENhbGwgICAgOiBBcHBcQ29udHJvbGxlclxIb21lQ29udHJvbGxlci0+aW5kZXhBY3Rpb24K');
        });
        $varDumper = new BacktraceDumper($output);
        $varDumper->dump(10, 0, $fakeTrace);


        $output = new CliOutput(function ($dumped) use($fakeTrace) {
            $this->assertEquals(base64_encode($dumped), "QmFja3RyYWNlIChsYXN0IDIgY2FsbHMpOgoKCiMxCiAgRmlsZSAgICA6IC92YXIvd3d3L2FwcC92ZW5kb3Ivc3ltZm9ueS9odHRwLWtlcm5lbC9IdHRwS2VybmVsLnBocDoxNTgKICBDYWxsICAgIDogU3ltZm9ueVxDb21wb25lbnRcSHR0cEtlcm5lbFxIdHRwS2VybmVsLT5oYW5kbGVSYXcKIzIKICBGaWxlICAgIDogL3Zhci93d3cvYXBwL3NyYy9Db250cm9sbGVyL0hvbWVDb250cm9sbGVyLnBocDo0MgogIENhbGwgICAgOiBBcHBcQ29udHJvbGxlclxIb21lQ29udHJvbGxlci0+aW5kZXhBY3Rpb24K");
        });
        $varDumper = new BacktraceDumper($output);
        $varDumper->dump(2, 0, $fakeTrace);
    }
}
