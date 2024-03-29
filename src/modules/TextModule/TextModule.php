<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\TextModule;

use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\ModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\TextContentUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TextModule extends Module
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule
    {
        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->addContent(
            (new TextContentUi($this->getAlias()))
        );

        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new MainItemUi($this->getAlias(), $this->getName(), valueData: $this->getName() . '|' . 'getContent');
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function getContent(array $parameters): IModuleResponse|null
    {
        $contentContainerUi = $parameters['contentContainerUi'] ?? null;
        if (!($contentContainerUi instanceof IContentContainerUi)) {
            return null;
        }

        $this->builderAdminContentUi($contentContainerUi);
        return (new ModuleResponse($parameters['request_id'] ?? null))
            ->setResponseHtml($contentContainerUi->getHtml());
    }
}
