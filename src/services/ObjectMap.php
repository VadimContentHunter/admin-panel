<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;

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
        return strtolower(preg_replace('~(?<!^)[A-Z]~', '_$0', $source) ?? '');
    }

    /**
     * @return string[]
     */
    public static function convertClassPropertiesToDbFormat(string $className): array
    {
        if (!class_exists($className)) {
            throw new AdminPanelException("Error, class not found.");
        }

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

    /**
     * @return array<string, mixed>
     */
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
