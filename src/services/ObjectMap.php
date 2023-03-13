<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMap
{
    /**
     * @return ReflectionProperty[]
     *
     * @throws AdminPanelException
     */
    protected static function getProperties(string|object $class_name_or_object): array
    {
        if (is_string($class_name_or_object) && class_exists($class_name_or_object)) {
            $reflector = new ReflectionClass($class_name_or_object);
        } elseif (is_object($class_name_or_object)) {
            $reflector = new ReflectionObject($class_name_or_object);
        } else {
            throw new AdminPanelException("Error, class or object not found.");
        }

        $properties = [];

        foreach ($reflector->getProperties() as $key => $property) {
            if (!($property instanceof ReflectionProperty)) {
                continue;
            }

            $attributes = $property->getAttributes(NotInDb::class);
            if (count($attributes) === 0) {
                $properties[] = $property;
            }
        }

        return $properties;
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
     *
     * @throws AdminPanelException
     */
    public static function convertClassPropertiesToDbFormat(string $className): array
    {
        if (!class_exists($className)) {
            throw new AdminPanelException("Error, class not found.");
        }

        $properties = self::getProperties($className);

        $mappedProperties = [];
        foreach ($properties as $property) {
            if (!($property instanceof ReflectionProperty)) {
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
        $properties = self::getProperties($object);

        $mappedProperties = [];
        foreach ($properties as $property) {
            if (!($property instanceof ReflectionProperty)) {
                continue;
            }
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = self::camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $property->getValue($object);
        }

        return $mappedProperties;
    }
}
