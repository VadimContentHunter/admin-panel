<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\templates\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class Module extends ActiveRecord implements IModule
{
    protected string $title = '';

    protected int $status = StatusCode::ERROR;

    /**
     * @var array<string,mixed>
     */
    protected array $data = [];

    protected ?string $pathConfig = null;

    public function setTitle(string $title): IModule
    {
        $this->title = $title;
        return $this;
    }

    public function setStatus(int $status): IModule
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Устанавливает данные, которые обрабатываются в админке.
     *
     * @param array<string,mixed> $data
     */
    public function setData(array $data): IModule
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param null|string $path_config null - лежит в текущей директории
     */
    public function setPathConfig(?string $path_config = null): IModule
    {
        $this->pathConfig = $path_config;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Возвращает данные, которые обрабатываются в админке.
     *
     * @return array<string,mixed> $data
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return null|string null - лежит в текущей директории
     */
    public function getPathConfig(): ?string
    {
        return $this->pathConfig ?? __DIR__ . '\\' . (preg_replace('~.*[\\\/](\w+)~u', '${1}', static::class . 'Config.json') ?? '');
    }

    abstract public function getAdminContentUi(): IContentContainerUi;

    public function initializeJsonConfig(): IModule
    {
        $path = $this->getPathConfig();
        if (!preg_match('~.*\.json~u', $path)) {
            throw new AdminPanelException('Error, the specified module config path is not json');
        }

        $temp = new \stdClass();
        $temp->title = $this->getTitle();
        $temp->status = $this->getStatus();
        $temp->data = $this->getData();
        $temp->pathConfig = $path;

        $json = json_encode($temp, JSON_UNESCAPED_UNICODE);

        file_put_contents($path, $json, FILE_APPEND | LOCK_EX);
        return $this;
    }

    public function updateJsonConfig(): IModule
    {
        return $this;
    }

    public static function getTableName(): string
    {
        return 'modules';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName((DB::$connector?->getDatabaseName()) ?? '')) {
            $db = new DB();
            $db->singleRequest()
                ->singleQuery(
                    (new TableMySQLQueryBuilder())
                        ->create(self::getTableName())
                            ->addField('id', FieldDataType::INT, [
                                FieldAttributes::AUTO_INCREMENT,
                                FieldAttributes::PRIMARY_KEY
                            ])
                            ->addField('title', FieldDataType::getTypeVarchar(80), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                            ->addField('status', FieldDataType::INT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('data', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('path_config', FieldDataType::getTypeVarchar(80), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                )
                ->send();

            return true;
        }
        return false;
    }
}
