<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\models\Module\ModuleConfig;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModuleConfig;
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

    protected ?string $pathModule = null;

    // protected string $lastModifiedDateTime;

    #[NotInDb]
    protected IModuleConfig $moduleConfig;

    abstract public function getAdminContentUi(): IContentContainerUi;

    final public function __construct(
        ?IModuleConfig $moduleConfig = null
    ) {
        $this->moduleConfig = $moduleConfig ?? new ModuleConfig(static::class);
        $this->status = StatusCode::ON;
    }

    /**
     * @throws AdminPanelException
     */
    public function initializeNewObject(): IModule
    {
        if ($this->title === '') {
            try {
                $this->title = $this->moduleConfig->initializeObjectFromModuleConfig()->getTitle();
            } catch (\Exception $e) {
                $this->title = self::initializeTitle();
            }
        }

        $object = self::selectByField('title', $this->title)[0] ?? null;
        if ($object === null) {
            $object = new static();
            $object->setTitle($this->title);
            $object->setStatus($this->status);
            $object->setData($this->getData());
            $object->setPathConfig($this->pathConfig);
            $object->setPathModule($this->pathModule);
            $object->insertObjectToDb();

            $object = self::selectByField('title', $this->title)[0] ?? null;
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
    public function initializeReplaceThisObject(): IModule
    {
        $object = $this->initializeNewObject();

        $this->setTitle($object->getTitle());
        $this->setStatus($object->getStatus());
        $this->setData($object->getData());
        $this->setPathConfig($object->getPathConfig());
        $this->setPathModule($object->getPathModule());

        return $this;
    }

    protected static function initializeTitle(): string
    {
        if (strcmp(static::class, self::class) === 0) {
            throw new AdminPanelException('Error class does not exist.');
        }

        $reflection = new \ReflectionClass(static::class);
        return $reflection->getShortName();
    }

    public function initializeJsonConfig(): IModule
    {
        $temp = new \stdClass();
        $temp->title = $this->getTitle();
        $temp->status = $this->getStatus();
        $temp->data = $this->getData();
        $temp->pathConfig = $this->getPathConfig();
        $temp->pathModule = $this->getPathModule();

        $json = json_encode($temp, JSON_UNESCAPED_UNICODE);
        file_put_contents($temp->pathConfig, $json, LOCK_EX);
        return $this;
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
        $this->pathConfig = $path_config ?? $this->moduleConfig->getDefaultPathConfig();
        return $this;
    }

    /**
     * @param null|string $path_module null - лежит в текущей директории
     */
    public function setPathModule(?string $path_module = null): IModule
    {
        $this->pathModule = $path_module ?? $this->moduleConfig->getDefaultPathModule();
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

    /**
     * @throws AdminPanelException
     */
    public function getPathConfig(): string
    {
        $path = $this->pathConfig ?? $this->moduleConfig->getDefaultPathConfig();
        if (!preg_match('~.*\.json~u', $path)) {
            throw new AdminPanelException('Error, the specified module config path is not json');
        }
        return $path;
    }

    public function getPathModule(): string
    {
        return $this->pathModule ?? $this->moduleConfig->getDefaultPathModule();
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
                            ->addField('path_module', FieldDataType::getTypeVarchar(80), [
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
