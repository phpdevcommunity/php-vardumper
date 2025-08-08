<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpDevCommunity\Debug\BacktraceDumper;
use PhpDevCommunity\Debug\Output\VarDumperOutput\HtmlOutput;
use PhpDevCommunity\Debug\VarDumper;

function _ddbt($data)
{
    $dumper = new VarDumper(new HtmlOutput());
    $backtraceDumper = new BacktraceDumper(new \PhpDevCommunity\Debug\Output\BacktraceOutput\HtmlOutput());
    $backtraceDumper->dump();
    $dumper->dump($data);
    die(1);
}

$dumper = new VarDumper(new HtmlOutput());
$data = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'active' => true,
    'roles' => ['admin', 'user'],
    'file' => new SplFileInfo(__FILE__)
];
$dumper->dump($data);

$data = new stdClass();
$data->name = 'John Doe';
$data->email = 'john.doe@example.com';
$data->active = true;
$dumper->dump($data);


$dumper->dump(true);
$dumper->dump(false);


$data = "Hello\nWorld\t😊";
$dumper->dump($data);

$data = [
    'string' => 'Hello world',
    'int' => 42,
    'float' => 3.14,
    'boolTrue' => true,
    'boolFalse' => false,
    'nullValue' => null,
    'arraySimple' => [1, 2, 3],
    'arrayNested' => [
        'level1' => [
            'level2' => [
                'level3a' => 'deep',
                'level3b' => [4, 5, 6]
            ],
            'level2b' => 'mid'
        ],
        'anotherKey' => 'value'
    ],
    'objectSimple' => (object)['foo' => 'bar', 'baz' => 123],
];
$func = function () use ($data) {
    _ddbt($data);
};
$func();