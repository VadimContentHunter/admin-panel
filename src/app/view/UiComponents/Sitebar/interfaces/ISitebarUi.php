<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces;

use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ISitebarUi
{
    /**
     * @return array<IBaseUiComponent>
     */
    public function setLogo(string $logo_path): ISitebarUi;

    public function addMenuMainItem( IMainItemUi $main_item ): ISitebarUi;

    public function addMenuModuleItem( IModuleItemUi $module_item ): ISitebarUi;
}
