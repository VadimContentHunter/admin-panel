<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMap
{
    public function __construct()
    {
    }

    public static function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    public static function convertPropertiesToDbFormat($className): array
    {
        $reflector = new \ReflectionClass($className);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            if (!($property instanceof \ReflectionProperty)) {
                continue;
            }
            $mappedProperties[] = $property->getName();
        }

        return $mappedProperties;
    }
}
