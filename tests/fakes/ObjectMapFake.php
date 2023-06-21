<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use ReflectionProperty;
use vadimcontenthunter\AdminPanel\services\ObjectMap;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMapFake extends ObjectMap
{
    /**
     * @return ReflectionProperty[]
     */
    public static function fakeGetProperties(object|string $class_name_or_object): array
    {
        return self::getProperties($class_name_or_object);
    }
}
