<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\UserAccountModule;

use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
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
    /**
     * @param array<string, mixed> $parameters
     */
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule
    {
        $user = null;
        if ($parameters['user'] && $parameters['user'] instanceof IUser) {
            $user = $parameters['user'];
        }

        $content_parameters = [
            'title' => $this->getAlias(),
            'user_name' => ($user?->getName()) ?? '',
            'user_email' => ($user?->getEmail()) ?? '',
        ];

        $contentContainerUi->setTitle($this->getAlias());
        $contentContainerUi->addContent(
            (new ContentItemUi(
                $content_parameters,
                AdminPanelSetting::getPathToTemplatesForModules($this->getName(), '/admin')
            ))
            ->setContent('account-content.php')
            ->setGridColumnCount(3)
        );

        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new ModuleItemUi($this->getAlias(), $this->getName(), valueData: $this->getName() . '|' . 'getContent');
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function getContent(array $parameters): JsonRpcResponse|null
    {
        $contentContainerUi = $parameters['contentContainerUi'] ?? null;
        if (!($contentContainerUi instanceof IContentContainerUi)) {
            return null;
        }

        $this->builderAdminContentUi($contentContainerUi, $parameters);
        return new JsonRpcResponse($contentContainerUi->getHtml(), $parameters['request_id'] ?? null);
    }
}
