<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\UserAccountModule;

use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\ContentItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class UserAccountModule extends Module
{
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi): IModule
    {
        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->addContent(
            (new ContentItemUi($this->getAlias()))
        );

        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new ModuleItemUi($this->getAlias(), $this->getName());
    }
}
