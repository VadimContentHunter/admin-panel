<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module\interfaces;

use vadimcontenthunter\AdminPanel\models\Module\StatusCode;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModule
{
    public function initializeNewObject(): IModule;

    public function initializeReplaceThisObject(): IModule;

    public function initializeJsonConfig(): IModule;

    public function copyData(IModule $module): IModule;

    /**
     * Название модуля, которое соответствует названию класса и файла Модуля
     */
    public function setName(string $name): IModule;

    /**
     * Устанавливает Название не связанное с Названием файла и класса для модуля.
     */
    public function setAlias(string $alias): IModule;

    public function setStatus(int $status): IModule;

    /**
     * Устанавливает данные, которые обрабатываются в админке.
     *
     * @param array<string,mixed> $data
     */
    public function setData(array $data): IModule;

    /**
     * @param string|null $path_config null - лежит в текущей директории
     */
    public function setPathConfig(string $path_config = null): IModule;

    public function setPathModule(?string $path_module = null): IModule;

    public function setLastModifiedDateTime(string|int $data_time): IModule;

    public function setFormatDateTime(string $format): IModule;

    public function getName(): string;

    public function getAlias(): string;

    public function getStatus(): int;

    /**
     * Возвращает данные, которые обрабатываются в админке.
     *
     * @return array<string,mixed>
     *
     * @throws AdminPanelException
     */
    public function getData(): array;

    public function getPathConfig(): string;

    public function getPathModule(): string;

    public function getLastModifiedDateTime(): string;

    public function getFormatDateTime(): string;

    public function builderAdminContentUi(IContentContainerUi $contentContainerUi): IModule;

    public function getMenuItem(): IModuleItemUi;
}
