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

    public static function convertClassPropertiesToDbFormat(string $className): array
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

    public static function convertObjectPropertiesToDbFormat(object $object): array
    {
        $reflector = new \ReflectionObject($object);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            if (!($property instanceof \ReflectionProperty)) {
                continue;
            }
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = self::camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $property->getValue($object);
        }

        return $mappedProperties;
    }
}
