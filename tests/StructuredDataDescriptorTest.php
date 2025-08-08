<?php

namespace Test\PhpDevCommunity\Debug;

use PhpDevCommunity\Debug\Util\DataDescriptor;
use PhpDevCommunity\UniTester\TestCase;

class StructuredDataDescriptorTest extends TestCase
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
        $result = DataDescriptor::describe([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'active' => true,
            'roles' => ['admin', 'user'],
            'age' => 30
        ]);

        $this->assertEquals($result, array(
            'type' => 'array',
            'depth' => 0,
            'truncated' => false,
            'count' => 5,
            'value' => 'array(5)',
            'items' =>
                array(
                    'name' =>
                        array(
                            'type' => 'string',
                            'depth' => 1,
                            'truncated' => false,
                            'length' => 8,
                            'value' => '"John Doe"',
                        ),
                    'email' =>
                        array(
                            'type' => 'string',
                            'depth' => 1,
                            'truncated' => false,
                            'length' => 20,
                            'value' => '"john.doe@example.com"',
                        ),
                    'active' =>
                        array(
                            'type' => 'boolean',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 'true',
                        ),
                    'roles' =>
                        array(
                            'type' => 'array',
                            'depth' => 1,
                            'truncated' => false,
                            'count' => 2,
                            'value' => 'array(2)',
                            'items' =>
                                array(
                                    0 =>
                                        array(
                                            'type' => 'string',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'length' => 5,
                                            'value' => '"admin"',
                                        ),
                                    1 =>
                                        array(
                                            'type' => 'string',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'length' => 4,
                                            'value' => '"user"',
                                        ),
                                ),
                        ),
                    'age' =>
                        array(
                            'type' => 'int',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 30,
                        ),
                ),
        ));

        $result = DataDescriptor::describe([
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
        ]);

        $this->assertEquals($result, array(
            'type' => 'array',
            'depth' => 0,
            'truncated' => false,
            'count' => 9,
            'value' => 'array(9)',
            'items' =>
                array(
                    'string' =>
                        array(
                            'type' => 'string',
                            'depth' => 1,
                            'truncated' => false,
                            'length' => 11,
                            'value' => '"Hello world"',
                        ),
                    'int' =>
                        array(
                            'type' => 'int',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 42,
                        ),
                    'float' =>
                        array(
                            'type' => 'float',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 3.14,
                        ),
                    'boolTrue' =>
                        array(
                            'type' => 'boolean',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 'true',
                        ),
                    'boolFalse' =>
                        array(
                            'type' => 'boolean',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 'false',
                        ),
                    'nullValue' =>
                        array(
                            'type' => 'NULL',
                            'depth' => 1,
                            'truncated' => false,
                            'value' => 'null',
                        ),
                    'arraySimple' =>
                        array(
                            'type' => 'array',
                            'depth' => 1,
                            'truncated' => false,
                            'count' => 3,
                            'value' => 'array(3)',
                            'items' =>
                                array(
                                    0 =>
                                        array(
                                            'type' => 'int',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'value' => 1,
                                        ),
                                    1 =>
                                        array(
                                            'type' => 'int',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'value' => 2,
                                        ),
                                    2 =>
                                        array(
                                            'type' => 'int',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'value' => 3,
                                        ),
                                ),
                        ),
                    'arrayNested' =>
                        array(
                            'type' => 'array',
                            'depth' => 1,
                            'truncated' => false,
                            'count' => 2,
                            'value' => 'array(2)',
                            'items' =>
                                array(
                                    'level1' =>
                                        array(
                                            'type' => 'array',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'count' => 2,
                                            'value' => 'array(2)',
                                            'items' =>
                                                array(
                                                    'level2' =>
                                                        array(
                                                            'type' => 'array',
                                                            'depth' => 3,
                                                            'truncated' => false,
                                                            'count' => 2,
                                                            'value' => 'array(2)',
                                                            'items' =>
                                                                array(
                                                                    'level3a' =>
                                                                        array(
                                                                            'type' => 'string',
                                                                            'depth' => 4,
                                                                            'truncated' => false,
                                                                            'length' => 4,
                                                                            'value' => '"deep"',
                                                                        ),
                                                                    'level3b' =>
                                                                        array(
                                                                            'type' => 'array',
                                                                            'depth' => 4,
                                                                            'truncated' => false,
                                                                            'count' => 3,
                                                                            'value' => 'array(3)',
                                                                            'items' =>
                                                                                array(
                                                                                    0 =>
                                                                                        array(
                                                                                            'type' => 'integer',
                                                                                            'depth' => 5,
                                                                                            'truncated' => true,
                                                                                            'value' => '…',
                                                                                        ),
                                                                                    1 =>
                                                                                        array(
                                                                                            'type' => 'integer',
                                                                                            'depth' => 5,
                                                                                            'truncated' => true,
                                                                                            'value' => '…',
                                                                                        ),
                                                                                    2 =>
                                                                                        array(
                                                                                            'type' => 'integer',
                                                                                            'depth' => 5,
                                                                                            'truncated' => true,
                                                                                            'value' => '…',
                                                                                        ),
                                                                                ),
                                                                        ),
                                                                ),
                                                        ),
                                                    'level2b' =>
                                                        array(
                                                            'type' => 'string',
                                                            'depth' => 3,
                                                            'truncated' => false,
                                                            'length' => 3,
                                                            'value' => '"mid"',
                                                        ),
                                                ),
                                        ),
                                    'anotherKey' =>
                                        array(
                                            'type' => 'string',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'length' => 5,
                                            'value' => '"value"',
                                        ),
                                ),
                        ),
                    'objectSimple' =>
                        array(
                            'type' => 'object',
                            'depth' => 1,
                            'truncated' => false,
                            'class' => 'stdClass',
                            'object_id' => 8,
                            'value' => 'stdClass#8',
                            'properties' =>
                                array(
                                    'foo' =>
                                        array(
                                            'type' => 'string',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'length' => 3,
                                            'value' => '"bar"',
                                        ),
                                    'baz' =>
                                        array(
                                            'type' => 'int',
                                            'depth' => 2,
                                            'truncated' => false,
                                            'value' => 123,
                                        ),
                                ),
                        ),
                ),
        ));
    }
}
