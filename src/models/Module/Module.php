<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class Module extends ActiveRecord implements IModule
{
    protected string $title = '';

    protected int $status = StatusCode::ERROR;

    protected string $data = '{}';

    protected ?string $pathConfig = null;

    final public function __construct()
    {
    }

    protected static function getDefaultPathConfig(): string
    {
        return __DIR__ . '\\' . (preg_replace('~.*[\\\/](\w+)~u', '${1}', static::class . 'Config.json') ?? '');
    }

    /**
     * @throws AdminPanelException
     */
    public static function initializeObject(?string $title = null, int $status = StatusCode::ON, ?string $path_config = null): IModule
    {
        if ($title === null) {
            $title = self::initializeObjectFromModuleConfig()->getTitle();
        }

        $object = self::selectByField('title', $title)[0] ?? null;
        if ($object === null) {
            $object = new static();
            $object->setTitle($title);
            $object->setStatus($status);
            $object->setPathConfig($path_config);
            $object->insertObjectToDb();

            $object = self::selectByField('title', $title)[0] ?? null;
            if ($object instanceof IModule) {
                $object->initializeJsonConfig();
            } else {
                throw new AdminPanelException('Error, unable to convert data from json format');
            }
        }
        return $object;
    }

    /**
     * @throws AdminPanelException
     */
    public static function initializeObjectFromModuleConfig(?string $path_config = null): IModule
    {
        $dataFromFile = file_get_contents(self::getDefaultPathConfig());
        if (!is_string($dataFromFile)) {
            throw new AdminPanelException("Error failed to read file.");
        }

        $arrDataForObject = json_decode($dataFromFile, true);
        if (
            !is_array($arrDataForObject)
            || $arrDataForObject['title']
            || $arrDataForObject['status']
            || $arrDataForObject['pathConfig']
        ) {
            throw new AdminPanelException("Error Incorrect data received from file.");
        }

        $data = json_decode($arrDataForObject['data'] ?? '', true);
        if (!is_array($data)) {
            throw new AdminPanelException("Error failed to convert module data to array.");
        }

        if (
            !is_string($arrDataForObject['title'])
            || !is_numeric($arrDataForObject['status'])
            || !is_string($arrDataForObject['pathConfig'])
        ) {
            throw new AdminPanelException("Error Incorrect type for the parameter. Must be a string.");
        }

        $object = new static();
        $object->setTitle($arrDataForObject['title']);
        $object->setStatus((int) $arrDataForObject['status']);
        $object->setData($data);
        $object->setPathConfig($arrDataForObject['pathConfig']);
        return $object;
    }

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
        $json = json_encode($data);
        $this->data = is_string($json) ? $json : throw new AdminPanelException("Error failed to convert to json string.");
        return $this;
    }

    /**
     * @param null|string $path_config null - лежит в текущей директории
     */
    public function setPathConfig(?string $path_config = null): IModule
    {
        $this->pathConfig = $path_config ?? self::getDefaultPathConfig();
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
     *
     * @throws AdminPanelException
     */
    public function getData(): array
    {
        $data = json_decode($this->data, true);
        if (!is_array($data)) {
            throw new AdminPanelException("Error failed to convert module data to array.");
        }

        return $data;
    }

    public function getPathConfig(): string
    {
        return $this->pathConfig ?? self::getDefaultPathConfig();
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

        file_put_contents($path, $json, LOCK_EX);
        return $this;
    }

    public static function getTableName(): string
    {
        return 'modules';
    }

    public static function createTable(): bool
    {
        if (!self::isTableName()) {
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
