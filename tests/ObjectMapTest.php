<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\AdminPanel\services\ObjectMap;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMapTest extends TestCase
{
    #[DataProvider('providerConvertClassPropertiesToDbFormat')]
    public function test_convertClassPropertiesToDbFormat_shouldReturnAnArrayWithObjectProperties(object $testObject, array $expected): void
    {
        $this->assertEquals($expected, ObjectMap::convertClassPropertiesToDbFormat($testObject::class));
    }

    /**
     * @return array<string,mixed>
     */
    public function providerConvertClassPropertiesToDbFormat(): array
    {
        return [
            'test 1' => [
                new class {
                    protected string $name = 'testObject';
                    protected string $type = 'test';
                    protected string $value = 'testValue';
                },
                [
                    'name',
                    'type',
                    'value',
                ],
            ],
        ];
    }

    #[DataProvider('providerConvertObjectPropertiesToDbFormat')]
    public function test_convertObjectPropertiesToDbFormat_shouldReturnAnArrayWithObjectProperties(object $testObject, array $expected): void
    {
        $this->assertEquals($expected, ObjectMap::convertObjectPropertiesToDbFormat($testObject));
    }

    /**
     * @return array<string,mixed>
     */
    public function providerConvertObjectPropertiesToDbFormat(): array
    {
        $object = new \stdClass();
        $object->name = 'testObject';
        $object->type = 'test';
        $object->value = 'testValue';

        return [
            'test 1' => [
                $object,
                [
                    'name' => 'testObject',
                    'type' => 'test',
                    'value' => 'testValue',
                ],
            ],
        ];
    }
}
