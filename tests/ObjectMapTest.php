<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests;

use ReflectionProperty;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\tests\fakes\ObjectMapFake;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMapTest extends TestCase
{
    /**
     * @param mixed[] $expected
     */
    #[DataProvider('providerConvertClassPropertiesToDbFormat')]
    public function test_convertClassPropertiesToDbFormat_shouldReturnAnArrayWithObjectProperties(object $test_object, array $expected): void
    {
        $this->assertEquals($expected, ObjectMap::convertClassPropertiesToDbFormat($test_object::class));
    }

    /**
     * @return array<string,mixed>
     */
    public static function providerConvertClassPropertiesToDbFormat(): array
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

    /**
     * @param mixed[] $expected
     */
    #[DataProvider('providerConvertObjectPropertiesToDbFormat')]
    public function test_convertObjectPropertiesToDbFormat_shouldReturnAnArrayWithObjectProperties(object $test_object, array $expected): void
    {
        $this->assertEquals($expected, ObjectMap::convertObjectPropertiesToDbFormat($test_object));
    }

    /**
     * @return array<string,mixed>
     */
    public static function providerConvertObjectPropertiesToDbFormat(): array
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


     /**
     * @param mixed[] $expected
     */
    #[DataProvider('providerGetProperties')]
    public function test_getProperties_withObject_shouldReturnAnArrayWithObjectOrClassProperties(object $object, array $expected): void
    {
        $actual = [];
        foreach (ObjectMapFake::fakeGetProperties($object) as $key => $property) {
            if (!($property instanceof ReflectionProperty)) {
                continue;
            }

            $actual[$property->getName()] = $property->getValue($object);
        }
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<string,mixed>
     */
    public static function providerGetProperties(): array
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
            'test 2' => [
                new class {
                    protected string $name = 'testObject';
                    protected string $type = 'test';
                    protected string $value = 'testValue';

                    #[NotInDb]
                    protected string $unnecessary_property = 'unnecessary_property';
                },
                [
                    'name' => 'testObject',
                    'type' => 'test',
                    'value' => 'testValue',
                ],
            ],
        ];
    }
}
