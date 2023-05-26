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
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeNone;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleResponseController
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function response(array $parameters): void
    {
        $response = new JsonRpcResponse(error: new JsonRpcError(1, 'module name not found!.'), id: $parameters['request_id'] ?? null);

        if (
            $parameters['modules']
            && $parameters['module_name']
            && $parameters['module_method']
            && $parameters['route']
            && is_array($parameters['modules'])
        ) {
            $module_method = $parameters['module_method'];
            $module = Module::searchByName($parameters['modules'], $parameters['module_name']);
            if ($module instanceof Module) {
                if (method_exists($module, $module_method)) {
                    $user = MainController::setAccessToUser(Helper::getCurrentHostUrl() . '/admin/login');
                    $adminPageUi = MainController::getAdminPageUiFactory($user);
                    $parameters['contentContainerUi'] = $adminPageUi->getContentComponent();
                    $parameters['user'] = $user;

                    $responseModule = $module->$module_method($parameters);
                    if ($responseModule instanceof IModuleResponse) {
                        $response = $responseModule->getResponse();
                    } else {
                        $response = new JsonRpcResponse(error: new JsonRpcError(2, 'This module not found!'), id: $parameters['request_id'] ?? null);
                    }
                } else {
                    $response = new JsonRpcResponse(error: new JsonRpcError(2, 'method for module not found!'), id: $parameters['request_id'] ?? null);
                }
            }
        }

        echo $response->getJsonRequest();
    }
}
