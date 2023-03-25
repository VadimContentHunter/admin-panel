<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\tests\fakes;

use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\services\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleFake extends Module
{
    public function getAdminContentUi(): IContentContainerUi
    {
        return new ContentContainerUi('ModuleFake', AdminPanelSetting::getPathToTemplates());
    }

    public function builderAdminContentUi(IContentContainerUi $contentContainerUi): IModule
    {
        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new MainItemUi('Текстовый модуль', $this->getName());
    }

    public function getRoutingForModule(array $parameters): Routing
    {
        $routing = new Routing();
        return $routing;
    }
}
