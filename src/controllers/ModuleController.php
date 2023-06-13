<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\views\RenderResponse;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;
use vadimcontenthunter\AdminPanel\routing\interfaces\IRoute;
use vadimcontenthunter\AdminPanel\controllers\MainController;
use vadimcontenthunter\AdminPanel\controllers\UserController;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeNone;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleController
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function view(array $parameters): void
    {
        echo '<h1>Module Controller</h1>';
        var_dump($parameters);
    }
}
