<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use ReflectionProperty;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ObjectMap
{
    public function __construct(
        protected object $object
    ) {
    }

    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    public function convertPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this->object);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            if (!($property instanceof \ReflectionProperty)) {
                continue;
            }
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $property->getValue($this->object);
        }

        return $mappedProperties;
    }
}
