<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\ChatGptTests;

use ReflectionProperty;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\AdminPanel\tests\fakes\DummyClassFake;
use vadimcontenthunter\AdminPanel\tests\fakes\ObjectMapFake;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;

class ChatGptObjectMapTest extends TestCase
{
    public function testGetPropertiesWithClassName(): void
    {
        $expected = [
            new ReflectionProperty(DummyClassFake::class, 'foo'),
            new ReflectionProperty(DummyClassFake::class, 'bar'),
        ];
        $actual = ObjectMapFake::fakeGetProperties(DummyClassFake::class);
        $this->assertEquals($expected, $actual);
    }

    public function testGetPropertiesWithObject(): void
    {
        $dummy = new DummyClassFake();
        $expected = [
            new ReflectionProperty(DummyClassFake::class, 'foo'),
            new ReflectionProperty(DummyClassFake::class, 'bar'),
        ];
        $actual = ObjectMapFake::fakeGetProperties($dummy);
        $this->assertEquals($expected, $actual);
    }

    public function testCamelCaseToUnderscore(): void
    {
        $expected = 'camel_case';
        $actual = ObjectMapFake::camelCaseToUnderscore('CamelCase');
        $this->assertEquals($expected, $actual);
    }

    public function testUnderscoreToCamelCase(): void
    {
        $expected = 'camelCase';
        $actual = ObjectMapFake::underscoreToCamelCase('camel_case');
        $this->assertEquals($expected, $actual);
    }

    public function testConvertClassPropertiesToDbFormat(): void
    {
        $expected = ['foo', 'bar'];
        $actual = ObjectMapFake::convertClassPropertiesToDbFormat(DummyClassFake::class);
        $this->assertEquals($expected, $actual);
    }

    public function testConvertObjectPropertiesToDbFormat(): void
    {
        $dummy = new DummyClassFake();
        $dummy->setFoo('foo_value');
        $dummy->setBar('bar_value');
        $expected = ['foo' => 'foo_value', 'bar' => 'bar_value'];
        $actual = ObjectMapFake::convertObjectPropertiesToDbFormat($dummy);
        $this->assertEquals($expected, $actual);
    }
}
