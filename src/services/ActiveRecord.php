<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder\DatabaseMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class ActiveRecord
{
    abstract public function getTableName(): string;

    abstract public static function createTable(): bool;

    public static function isTableName(string $db_name): bool
    {
        $db = new DB();
        $tableName =  $db->singleRequest()
            ->singleQuery(
                (new DatabaseMySQLQueryBuilder())
                    ->isTable($db_name, static::getTableName())
            )
            ->send()[0][0] ?? '';

        if (is_string($tableName) && strcmp($tableName, static::getTableName()) === 0) {
            return true;
        }
        return false;
    }

    /**
     * @return static[]|null
     */
    public static function selectAllFields(): ?array
    {
        if (static::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(static::getTableName())
            )
            ->setClassName(static::class)
            ->send();

        return count($objects) > 0 ? $objects : null;
    }

    public static function selectById(int $id): ?static
    {
        if (self::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(static::getTableName())
                            ->where('id=:id')
            )
            ->setClassName(static::class)
            ->addParameter(':id', $id)
            ->send();
        return count($objects) > 0 ? $objects[0] : null;
    }

    /**
     * @return static[]|null
     */
    public static function selectByField(string $field, string|int $field_value): ?array
    {
        if (static::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objects = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(static::getTableName())
                            ->where($field . '=:field')
            )
            ->setClassName(static::class)
            ->addParameter(':field', $field_value)
            ->send();

        return count($objects) > 0 ? $objects : null;
    }

    /**
     * @param array<string,string|int> $fields
     *
     * @return static[]|null
     */
    public static function selectByFields(array $fields): ?array
    {
        if (static::createTable() !== false) {
            return null;
        }

        $db = new DB();
        $objSingleRequest = $db->singleRequest()->setClassName(static::class);
        $query = (new DataMySQLQueryBuilder())->select()->addField('*')->from(static::getTableName());

        $first_element = true;
        foreach ($fields as $field_name => $field_value) {
            $parameter_name = ':field_' . $field_name;
            if ($first_element) {
                $query->where($field_name . '=' . $parameter_name);
                $first_element = false;
            } else {
                $query->and($field_name . '=' . $parameter_name);
            }

            $objSingleRequest->addParameter($parameter_name, $field_value);
        }

        $objects = $objSingleRequest->singleQuery($query)->send();

        return count($objects) > 0 ? $objects : null;
    }
}
