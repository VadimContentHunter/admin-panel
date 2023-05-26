<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\ExitModule;

use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\ModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ExitModule extends Module
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function builderAdminContentUi(IContentContainerUi $contentContainerUi, array $parameters = []): IModule
    {
        $contentContainerUi->setTitle($this->getAlias());
        return $this;
    }

    public function getMenuItem(): IModuleItemUi
    {
        return new MainItemUi($this->getAlias(), $this->getName(), valueData: $this->getName() . '|' . 'signOut');
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function signOut(array $parameters): IModuleResponse|null
    {
        User::deleteSessionData();
        return (new ModuleResponse($parameters['request_id'] ?? null))
            ->setResponseLocation(Helper::getCurrentHostUrl() . '/admin/login');
    }
}
