<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModuleItemUi extends IBaseUiComponent
{
    public function setActivateMenuItem(bool $flag): IModuleItemUi;

    public function setPathToTemplates(string $path_to_templates): IModuleItemUi;

    public function getRequestContent(): string;
}
