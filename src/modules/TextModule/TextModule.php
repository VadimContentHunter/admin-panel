<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\TextModule;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\TextContentUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TextModule extends Module
{
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi): IModule
    {
        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->addContent(
            (new TextContentUi($this->getAlias()))
        );

        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new MainItemUi($this->getAlias(), $this->getName());
    }
}
