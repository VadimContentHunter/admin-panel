<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder\DatabaseMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class ActiveRecord
{
    protected ?int $id = null;

    public function __set(string $name, mixed $value): void
    {
        $camelCaseName = 'set' . ObjectMap::underscoreToCamelCase($name, false);
        $this->$camelCaseName($value);
    }

    public function setId(int $id): static
    {
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    abstract public static function getTableName(): string;

    /**
     * @return bool Возвращает true, если таблица была создана иначе false.
     */
    abstract public static function createTable(): bool;

    public static function isTableName(): bool
    {
        $db = new DB();
        $tableName =  $db->singleRequest()
            ->singleQuery(
                (new DatabaseMySQLQueryBuilder())
                    ->isTable((DB::$connector?->getDatabaseName()) ?? '', static::getTableName())
            )
            ->send()[0][0] ?? '';

        if (is_string($tableName) && strcmp($tableName, static::getTableName()) === 0) {
            return true;
        }
        return false;
    }

    public static function dropTable(bool $table_check = true): bool
    {
        if ($table_check && !self::isTableName()) {
            return false;
        }

        $db = new DB();
        $db->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->drop(static::getTableName())
            )
            ->send();

        if (!self::isTableName()) {
            return true;
        }

        return false;
    }

    /**
     * @return static[]|null
     */
    public static function selectAllFields(): ?array
    {
        if (static::createTable()) {
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
        if (static::createTable()) {
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
        if (static::createTable()) {
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
        if (static::createTable()) {
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

    public static function selectLastRecord(): ?static
    {
        if (!static::isTableName()) {
            return null;
        }

        $db = new DB();
        $object = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(static::getTableName())
                            ->orderByDesc('id')
                            ->limit(1)
            )
            ->setClassName(static::class)
            ->send()[0];

        return $object instanceof static ? $object : null;
    }


    /**
     * @throws AdminPanelException
     */
    public function insertObjectToDb(): static
    {
        static::createTable();

        $query = (new DataMySQLQueryBuilder())->insert(static::getTableName());
        $mapProperties = ObjectMap::convertObjectPropertiesToDbFormat($this);
        $db = (new DB())->singleRequest();

        foreach ($mapProperties as $property_name => $property_value) {
            $parameter = ':field_' . $property_name;
            if ($property_name === 'id') {
                continue;
            }

            if (!is_string($property_value) && !is_numeric($property_value)) {
                throw new AdminPanelException('Error adding to database, data is not a string or number.');
            }

            $query->addValue($property_name, $parameter);
            $db->addParameter($parameter, (string)$property_value);
        }

        $db->singleQuery($query)->send();

        return $this;
    }

    /**
     * @throws AdminPanelException
     */
    public function insertReturnObjectToDb(): static
    {
        $this->insertObjectToDb();
        return self::selectLastRecord() ?? throw new AdminPanelException('Error Object not found in database');
    }

    /**
     * Использует данные в объекте
     *
     * @throws AdminPanelException
     */
    public function updateObjectToDbById(): static
    {
        static::createTable();

        $query = (new DataMySQLQueryBuilder())->update(static::getTableName());
        $mapProperties = ObjectMap::convertObjectPropertiesToDbFormat($this);
        $db = (new DB())->singleRequest();

        foreach ($mapProperties as $property_name => $property_value) {
            $parameter = ':field_' . $property_name;
            if (self::getId() === null) {
                throw new AdminPanelException('Database update error, id not specified.');
            }

            if (!is_string($property_value) && !is_numeric($property_value)) {
                throw new AdminPanelException('Database update error, data is not a string or number.');
            }

            $query->set($property_name, $parameter);
            $db->addParameter($parameter, (string)$property_value);
        }

        $db->singleQuery(
            $query->getOperators()
                ->where('id=' . self::getId())
        )
        ->send();
        return $this;
    }

    /**
     * Использует данные в объекте
     *
     * @throws AdminPanelException
     */
    public function updateObjectToDbByField(string $field, string|int $field_value): static
    {
        static::createTable();

        $query = (new DataMySQLQueryBuilder())->update(static::getTableName());
        $mapProperties = ObjectMap::convertObjectPropertiesToDbFormat($this);
        $db = (new DB())->singleRequest();

        foreach ($mapProperties as $property_name => $property_value) {
            $parameter = ':field_' . $property_name;
            if ($property_name === 'id' && $property_value === null) {
                continue;
            }

            if (!is_string($property_value) && !is_numeric($property_value)) {
                throw new AdminPanelException('Database update error, data is not a string or number.');
            }

            $query->set($property_name, $parameter);
            $db->addParameter($parameter, (string)$property_value);
        }

        $db->singleQuery(
            $query->getOperators()
                ->where($field . "='" . $field_value . "'")
        )
        ->send();

        return $this;
    }

    /**
     * Использует данные в объекте
     *
     * @param array<string,string|int> $fields
     *
     * @throws AdminPanelException
     */
    public function updateObjectToDbByFields(array $fields): static
    {
        static::createTable();

        $query = (new DataMySQLQueryBuilder())->update(static::getTableName());
        $mapProperties = ObjectMap::convertObjectPropertiesToDbFormat($this);
        $db = (new DB())->singleRequest();

        foreach ($mapProperties as $property_name => $property_value) {
            $parameter = ':field_' . $property_name;
            if ($property_name === 'id' && $property_value === null) {
                continue;
            }

            if (!is_string($property_value) && !is_numeric($property_value)) {
                throw new AdminPanelException('Database update error, data is not a string or number.');
            }

            $query->set($property_name, $parameter);
            $db->addParameter($parameter, (string)$property_value);
        }

        $query = $query->getOperators();
        $first_element = true;
        foreach ($fields as $field_name => $field_value) {
            if ($first_element) {
                $query->where($field_name . '="' . $field_value . '"');
                $first_element = false;
            } else {
                $query->and($field_name . '="' . $field_value . '"');
            }
        }

        $db->singleQuery($query)->send();
        return $this;
    }
}
