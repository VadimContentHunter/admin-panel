<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\modules\BlockManagement\exceptions\BlockException;
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
    protected ?string $parameters = null;
    protected ?string $pathBlockView = null;

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

    public function setPathBlockView(string $path): IBlock
    {
        if (!is_file($path)) {
            throw new BlockException('Файл к блоку для показа не существует.');
        }
        $this->pathBlockView = $path;
        return $this;
    }

    public function getPathBlockView(): string
    {
        if ($this->pathBlockView === null) {
            throw new BlockException('Путь к блоку для показа должен быть указан.');
        }

        if (!is_file($this->pathBlockView)) {
            throw new BlockException('Файл к блоку для показа не существует.');
        }
        return $this->pathBlockView;
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

    /**
     * @param array<string> $parameters
     */
    public function setParameters(array $parameters): IBlock
    {
        $json = json_encode($parameters);
        if (!is_string($json)) {
            throw new BlockException('Не удалось преобразовать параметры в json.');
        }
        $this->parameters = $json;
        return $this;
    }

    /**
     * @return array<string> $parameters
     */
    public function getParameters(): array
    {
        if ($this->parameters === null) {
            throw new BlockException('Параметры должны быть указаны.');
        }

        $params = json_decode($this->parameters, true);
        if (!is_array($params)) {
            throw new BlockException('Не удалось преобразовать json параметры в массив.');
        }
        return $params;
    }

    public function getParameter(string $key): ?string
    {
        $params = $this->getParameters();
        if (array_key_exists($key, $params)) {
            return $params[$key];
        }

        return null;
    }

    public static function getTableName(): string
    {
        return 'blocks';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery((new TableMySQLQueryBuilder())
                        ->create(static::getTableName())
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
                            ->addField('path_block_view', FieldDataType::TEXT, [
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
