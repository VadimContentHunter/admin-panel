<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module\interfaces;

use DateTime;
use vadimcontenthunter\AdminPanel\services\ActiveRecord;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModuleConfig
{
    public function initializeObjectFromModuleConfig(?string $path_config = null, ?string $path_module = null): IModule;

    public function getDefaultPathConfig(?string $path_module = null): string;

    public function getDefaultPathModule(): string;

    public function initializeJsonConfig(IModule $module): IModuleConfig;

    public function getDataTimeConfigJson(string $path_config): int;

    public function hasFileChanged(string $path_config, int $date_time): bool;

    public function writeDataDbToJsonConfig(string $title, ActiveRecord $object): IModuleConfig;
}
