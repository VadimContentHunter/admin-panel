<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ISitebarUi extends IBaseUiComponent
{
    public function setLogo(string $logo_path): ISitebarUi;

    public function addMenuMainItem(IMainItemUi $main_item): ISitebarUi;

    public function addMenuModuleItem(IModuleItemUi $module_item): ISitebarUi;

    public function activateFirstItemMenu(): ISitebarUi;
}
