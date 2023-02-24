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
    /**
     * @return array<string,mixed>
     */
    public function providerConvertPropertiesToDbFormat(): array
    {
        return [
            'test 1' => [
                new class {
                    protected string $name = "testObject";
                    protected string $type = "test";
                    protected string $value = "testValue";
                },
                [
                    'name' => 'testObject',
                    'type' => 'test',
                    'value' => 'testValue',
                ],
            ],
        ];
    }

    #[DataProvider('providerConvertPropertiesToDbFormat')]
    public function test_convertPropertiesToDbFormat_shouldReturnAnArrayWithObjectProperties(object $testObject, array $expected): void
    {
        $objectMap = new ObjectMap($testObject);
        $this->assertEquals($expected, $objectMap->convertPropertiesToDbFormat());
    }
}
