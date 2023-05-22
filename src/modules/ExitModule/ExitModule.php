<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\ExitModule;

use vadimcontenthunter\AdminPanel\routing\Routing;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Module\interfaces\IModule;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeData;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeHtml;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeNone;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\TextContentUi;
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
        return new MainItemUi($this->getAlias(), $this->getName());
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function getRoutingForModule(array $parameters): Routing
    {
        $routing = new Routing();
        $routing->addRoute('~GET/sign_out$~', self::class, 'signOut', $parameters);
        return $routing;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function signOut(array $parameters): AResponseType|null
    {
        User::deleteSessionData();
        return new ResponseTypeData(true, 0, ['location' => Helper::getCurrentHostUrl() . '/admin/login']);
    }
}