<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\exceptions\BlockException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\interfaces\IBlock;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Block extends ActiveRecord implements IBlock
{
    protected ?string $name = null;
    protected ?string $description = null;
    protected ?array $parameters = null;

    public function __construct()
    {
    }

    public function setName(string $name): IBlock
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        if ($this->name === null) {
            throw new BlockException('Имя должно быть указано.');
        }
        return $this->name;
    }

    public function setDescription(string $description): IBlock
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function setParameters(array $parameters): IBlock
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getParameters(): array
    {
        if ($this->parameters === null) {
            throw new BlockException('Параметры должны быть указаны.');
        }
        return $this->parameters;
    }

    public function getParameter(string $key): ?string
    {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }

        return null;
    }

    public static function getTableName(): string
    {
        return 'block';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery((new TableMySQLQueryBuilder())
                        ->create(self::getTableName())
                            ->addField('id', FieldDataType::INT, [
                                FieldAttributes::AUTO_INCREMENT,
                                FieldAttributes::PRIMARY_KEY
                            ])
                            ->addField('name', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('description', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('parameters', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ]))
                ->send();
            return true;
        }
        return false;
    }
}
