<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModuleConfig;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleConfig implements IModuleConfig
{
    /**
     * @throws AdminPanelException
     */
    public function __construct(
        protected string $className
    ) {
        if (!$this->checkClassName($this->className)) {
            throw new AdminPanelException('Error Incorrect class specified.');
        }
    }

    protected function checkClassName(string $class_name): bool
    {
        if (
            class_exists($class_name)
            && is_subclass_of($class_name, IModule::class)
            && strcmp($class_name, IModule::class) !== 0
        ) {
            return true;
        }
        return false;
    }

    /**
     * @throws AdminPanelException
     */
    public function getDataTimeConfigJson(string $path_config): int
    {
        if (!file_exists($path_config)) {
            throw new AdminPanelException('Error, file does not exist.');
        }
        $file_data_time = filemtime($path_config);
        if (!is_integer($file_data_time)) {
            throw new AdminPanelException('Error, unable to get file date and time.');
        }
        return $file_data_time;
    }

    /**
     * @throws AdminPanelException
     */
    public function hasFileChanged(string $path_config, int $date_time): bool
    {
        return $date_time < $this->getDataTimeConfigJson($path_config) ? true : false;
    }

    /**
     * @throws AdminPanelException
     */
    public function getDefaultPathModule(): string
    {
        if (!class_exists($this->className)) {
            throw new AdminPanelException('Error, Incorrect class specified.');
        }

        $reflection = new \ReflectionClass($this->className);
        $class_name = $reflection->getShortName();
        $file_name = $reflection->getFileName();
        $path_to_module = preg_replace('~[\\\/]' . $class_name . '\.php$~u', '', $file_name ?:  throw new AdminPanelException('Error, invalid file path.'));
        return is_string($path_to_module) ? $path_to_module : throw new AdminPanelException('Error, invalid file path.');
    }

    /**
     * @throws AdminPanelException
     */
    public function getDefaultPathConfig(?string $path_module = null): string
    {
        $file_config = ($path_module ?? self::getDefaultPathModule()) . '\\' . (preg_replace('~.*[\\\/](\w+)~u', '${1}', $this->className . 'Config.json') ?? '');
        return $file_config;
    }

    /**
     * @throws AdminPanelException
     */
    public function initializeObjectFromModuleConfig(?string $path_config = null): IModule
    {
        $dataFromFile = file_get_contents(self::getDefaultPathConfig());
        if (!is_string($dataFromFile)) {
            throw new AdminPanelException("Error failed to read file.");
        }

        $arrDataForObject = json_decode($dataFromFile, true);
        if (
            !is_array($arrDataForObject)
            || !$arrDataForObject['name']
            || !$arrDataForObject['alias']
            || !$arrDataForObject['status']
            || !$arrDataForObject['pathConfig']
            || !$arrDataForObject['pathModule']
            || !$arrDataForObject['lastModifiedDateTime']
            || !$arrDataForObject['formatDateTime']
        ) {
            throw new AdminPanelException("Error Incorrect data received from file.");
        }

        $data = [];
        if (is_array($arrDataForObject['data'])) {
            $data = $arrDataForObject['data'];
        } elseif (is_string($arrDataForObject['data'] ??= '')) {
            $data = json_decode($arrDataForObject['data'], true);
            if (!is_array($data)) {
                throw new AdminPanelException("Error failed to convert module data to array.");
            }
        }

        if (
            !is_string($arrDataForObject['name'])
            || !is_string($arrDataForObject['alias'])
            || !is_numeric($arrDataForObject['status'])
            || !is_string($arrDataForObject['pathConfig'])
            || !is_string($arrDataForObject['pathModule'])
            || !is_string($arrDataForObject['lastModifiedDateTime'])
            || !is_string($arrDataForObject['formatDateTime'])
        ) {
            throw new AdminPanelException("Error Incorrect type for the parameter. Must be a string.");
        }

        $object = new $this->className();

        if (!($object instanceof IModule)) {
            throw new AdminPanelException("Error failed to read file.");
        }

        $object->setName($arrDataForObject['name']);
        $object->setAlias($arrDataForObject['alias']);
        $object->setStatus((int) $arrDataForObject['status']);
        $object->setData($data);
        $object->setPathConfig($arrDataForObject['pathConfig']);
        $object->setPathModule($arrDataForObject['pathModule']);
        $object->setLastModifiedDateTime($arrDataForObject['lastModifiedDateTime']);
        $object->setFormatDateTime($arrDataForObject['formatDateTime']);
        return $object;
    }

    public function initializeJsonConfig(IModule $module): IModuleConfig
    {
        $temp = new \stdClass();
        $temp->alias = $module->getAlias();
        $temp->name = $module->getName();
        $temp->status = $module->getStatus();
        $temp->data = $module->getData();
        $temp->pathConfig = $module->getPathConfig();
        $temp->pathModule = $module->getPathModule();
        $temp->lastModifiedDateTime = $module->getLastModifiedDateTime();
        $temp->formatDateTime = $module->getFormatDateTime();

        $json = json_encode($temp, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($module->getPathConfig(), $json, LOCK_EX);
        return $this;
    }

    public function writeDataDbToJsonConfig(string $name, ActiveRecord $object): IModuleConfig
    {
        $object = $object::selectByField('name', $name)[0] ?? null;
        if ($object instanceof IModule) {
            $object->initializeJsonConfig();
        } else {
            throw new AdminPanelException('Error, unable to convert data from json format');
        }

        return $this;
    }
}
