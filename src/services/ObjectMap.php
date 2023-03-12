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

    public static function underscoreToCamelCase(string $source, bool $first_char_small = true): string
    {
        $camelCaseName = str_replace('_', '', ucwords($source, '_'));
        return $first_char_small ? lcfirst($camelCaseName) : $camelCaseName;
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
