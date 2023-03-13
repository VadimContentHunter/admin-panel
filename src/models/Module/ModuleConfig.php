<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModuleConfig;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleConfig implements IModuleConfig
{
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
            && is_subclass_of($class_name, Module::class)
            && strcmp($class_name, Module::class) !== 0
        ) {
            return true;
        }
        return false;
    }

    /**
     * @throws AdminPanelException
     */
    public function getDefaultPathModule(): string
    {
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

    public function initializeObjectFromModuleConfig(?string $path_config = null): IModule
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

        $object = new $this->className();
        $object->setTitle($arrDataForObject['title']);
        $object->setStatus((int) $arrDataForObject['status']);
        $object->setData($data);
        $object->setPathConfig($arrDataForObject['pathConfig']);
        return $object;
    }
}
