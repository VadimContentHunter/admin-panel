<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\UserAccountModule;

use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeHtml;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;
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

    /**
     * @param array<string,string> $parameters
     */
    public function getRoutingForModule(array $parameters): Routing
    {
        $routing = new Routing();
        $routing->addRoute('~GET/content$~', self::class, 'getContent', $parameters);
        return $routing;
    }

    /**
     * @param array<string,string> $parameters
     */
    public function getContent(array $parameters): AResponseType|null
    {
        $contentContainerUi = $parameters['contentContainerUi'] ?? null;
        if (!($contentContainerUi instanceof IContentContainerUi)) {
            return null;
        }

        $this->builderAdminContentUi($contentContainerUi);
        return new ResponseTypeHtml(true, 0, $contentContainerUi);
    }
}
