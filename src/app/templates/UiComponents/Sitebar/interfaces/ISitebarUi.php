<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\templates\UiComponents\Sitebar\interfaces;

use vadimcontenthunter\AdminPane\templates\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\templates\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPane\templates\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ISitebarUi
{
    public function setLogo(string $logo_path): ISitebarUi;

    public function addMenuMainItem(IMainItemUi $main_item): ISitebarUi;

    public function addMenuModuleItem(IModuleItemUi $module_item): ISitebarUi;
}
