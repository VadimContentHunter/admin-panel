<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module\interfaces;

use vadimcontenthunter\AdminPanel\templates\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModule
{
    public function setTitle(string $title): IModule;

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
    public function setPathConfig(?string $path_config = null): IModule;

    public function getTitle(): string;

    public function getStatus(): int;

    /**
     * Возвращает данные, которые обрабатываются в админке.
     *
     * @return array<string,mixed>
     */
    public function getData(): array;

    /**
     * @return string|null null - лежит в текущей директории
     */
    public function getPathConfig(): ?string;

    public function getAdminContentUi(): IContentContainerUi;

    public function initializeJsonConfig(): IModule;

    public function updateJsonConfig(): IModule;
}
