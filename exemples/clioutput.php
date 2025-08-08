<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use PhpDevCommunity\Debug\VarDumper;

$data = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'active' => true,
    'roles' => ['admin', 'user'],
    'file' => new SplFileInfo(__FILE__)
];

dump($data);

$data = new stdClass();
$data->name = 'John Doe';
$data->email = 'john.doe@example.com';
$data->active = true;
dump($data);

dump(true);
dump(false);


$data = "Hello\nWorld\t😊";
dump($data);

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
$func = function ()  use ($data) {
    dd_bt($data);
};
$func();