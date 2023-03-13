<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Module\interfaces;

use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModuleConfig
{
    public function initializeObjectFromModuleConfig(?string $path_config = null): IModule;

    public function getDefaultPathConfig(?string $path_module = null): string;

    public function getDefaultPathModule(): string;
}
