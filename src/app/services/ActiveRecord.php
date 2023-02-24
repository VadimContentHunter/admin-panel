<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\services;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder\DatabaseMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class ActiveRecord
{
    abstract public function getTableName(): string;

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

    public static function createTable(): bool
    {
        if (!self::isTableName(DB::$connector->getDatabaseName())) {
            $db = new DB();
            $objTableMySQLQueryBuilder = (new TableMySQLQueryBuilder())->create(static::getTableName());

            return true;
        }
        return false;
    }

    public static function getById(int $id): ?static
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
        return $objects ? $objects[0] : null;
    }

    /**
     * @return static[]|null
     */
    public static function getSelectedByAll(): ?array
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

        return is_array($objects) ? $objects : null;
    }
}
