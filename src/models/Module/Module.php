<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use DateTime;
use DateTimeImmutable;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\services\ObjectMap;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\models\Module\ModuleConfig;
use vadimcontenthunter\AdminPanel\services\attributes\NotInDb;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModuleConfig;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\exceptions\ModuleConfig\ReadFileException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class Module extends ActiveRecord implements IModule
{
    /**
     * Название не связанное с Названием файла и класса для модуля.
     * Может быть такой же как и поле name
     */
    protected string $alias = '';

    /**
     * Название модуля, которое соответствует названию класса и файла Модуля
     */
    protected string $name = '';

    protected int $status = StatusCode::ERROR;

    protected string $data = '{}';

    protected ?string $pathConfig = null;

    protected ?string $pathModule = null;

    protected string $lastModifiedDateTime = '';

    protected string $formatDateTime = 'Y-m-d H:i:s';

    #[NotInDb]
    protected DateTime $dataTime;

    #[NotInDb]
    protected IModuleConfig $moduleConfig;

    /**
     * @param array<string, mixed> $parameters
     */
    abstract public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule;

    abstract public function getMenuItem(): IModuleItemUi;

    abstract public function getRoutingForModule(array $parameters): Routing;

    final public function __construct(
        ?IModuleConfig $moduleConfig = null,
        #[NotInDb]
        DateTime $dataTime = new DateTime()
    ) {
        $this->moduleConfig = $moduleConfig ?? new ModuleConfig(static::class);
        $this->status = StatusCode::ON;
        $this->dataTime = $dataTime;
    }

    /**
     * @throws AdminPanelException
     */
    public function initializeNewObject(): IModule
    {
        try {
            $moduleJson = $this->moduleConfig->initializeObjectFromModuleConfig($this->pathConfig, $this->pathModule);
            if ($this->name === '') {
                $this->setName($moduleJson->getName());
            }

            if ($this->alias === '') {
                $this->setAlias($moduleJson->getAlias());
            }
            $this->pathConfig = $moduleJson->getPathConfig();
        } catch (ReadFileException $e) {
            if ($this->name === '') {
                $this->name = $this::initializeName();
            }
            if ($this->alias === '') {
                $this->alias = $this::initializeName();
            }
        }

        $object = self::selectByField('name', $this->name)[0] ?? null;
        if (!($object instanceof IModule)) {
            $object = new static();
            $object->setName($this->name);
            $object->setAlias($this->alias);
            $object->setStatus($this->status);
            $object->setData($this->getData());
            $object->setPathConfig($this->pathConfig);
            $object->setPathModule($this->pathModule);
            $object->setFormatDateTime($this->formatDateTime);
            $object->initializeJsonConfig();

            $file_data_time = $this->moduleConfig->getDataTimeConfigJson($object->getPathConfig());
            $object->setLastModifiedDateTime($file_data_time);
            $object->insertObjectToDb();
        } else {
            if (!file_exists($object->getPathConfig())) {
                $object->initializeJsonConfig();
            } else {
                $object_data_time = new DateTime($object->getLastModifiedDateTime());
                if ($this->moduleConfig->hasFileChanged($object->getPathConfig(), $object_data_time->getTimestamp())) {
                    $module = $this->moduleConfig->initializeObjectFromModuleConfig($object->getPathConfig());
                    $object->copyData($module);

                    $file_data_time = $this->moduleConfig->getDataTimeConfigJson($object->getPathConfig());
                    $object->setLastModifiedDateTime($file_data_time);
                    if ($object instanceof ActiveRecord) {
                        $object->updateObjectToDbById();
                    }
                }
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

        $this->setName($object->getName());
        $this->setAlias($object->getAlias());
        $this->setStatus($object->getStatus());
        $this->setData($object->getData());
        $this->setPathConfig($object->getPathConfig());
        $this->setPathModule($object->getPathModule());
        $this->setLastModifiedDateTime($object->getLastModifiedDateTime());
        $this->setFormatDateTime($object->getFormatDateTime());

        return $this;
    }

    protected static function initializeName(): string
    {
        if (strcmp(static::class, self::class) === 0) {
            throw new AdminPanelException('Error class does not exist.');
        }

        $reflection = new \ReflectionClass(static::class);
        return $reflection->getShortName();
    }

    public function initializeJsonConfig(): IModule
    {
        $this->moduleConfig->initializeJsonConfig($this);
        return $this;
    }

    public function copyData(IModule $module): IModule
    {
        $this->setName($module->getName());
        $this->setAlias($module->getAlias());
        $this->setStatus($module->getStatus());
        $this->setData($module->getData());
        $this->setPathConfig($module->getPathConfig());
        $this->setPathModule($module->getPathModule());
        $this->setLastModifiedDateTime($module->getLastModifiedDateTime());
        $this->setFormatDateTime($module->getFormatDateTime());

        return $this;
    }

    public function setName(string $name): IModule
    {
        if ($name === '') {
            $this->name = $this::initializeName();
        } else {
            $this->name = $name;
        }
        return $this;
    }

    public function setAlias(string $alias): IModule
    {
        if ($alias === '') {
            $this->alias = $this::initializeName();
        } else {
            $this->alias = $alias;
        }

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

    public function setLastModifiedDateTime(string|int $data_time): IModule
    {
        if (is_string($data_time)) {
            $this->dataTime = new DateTime($data_time);
        } else {
            $this->dataTime->setTimestamp($data_time);
        }

        $this->lastModifiedDateTime = $this->dataTime->format($this->getFormatDateTime());
        return $this;
    }

    public function setFormatDateTime(string $format): IModule
    {
        $this->formatDateTime = $format;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
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

    public function getLastModifiedDateTime(): string
    {
        return $this->lastModifiedDateTime;
    }

    public function getFormatDateTime(): string
    {
        return $this->formatDateTime;
    }

    /**
     * @param IModule[] $modules
     */
    public static function searchByName(array $modules, string $name): IModule|null
    {
        foreach ($modules as $key => $module) {
            if ($module instanceof IModule && $module->getName() == $name) {
                return $module;
            }
        }
        return null;
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
                            ->addField('alias', FieldDataType::getTypeVarchar(80), [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('name', FieldDataType::getTypeVarchar(80), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                            ->addField('status', FieldDataType::INT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('data', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('path_config', FieldDataType::getTypeVarchar(500), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                            ->addField('path_module', FieldDataType::getTypeVarchar(500), [
                                FieldAttributes::NOT_NULL,
                                FieldAttributes::UNIQUE
                            ])
                            ->addField('last_modified_date_time', FieldDataType::DATETIME, [
                                FieldAttributes::NOT_NULL
                            ])
                            ->addField('format_date_time', FieldDataType::TEXT, [
                                FieldAttributes::NOT_NULL
                            ])
                )
                ->send();

            return true;
        }
        return false;
    }
}
