<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\views\RenderResponse;
use vadimcontenthunter\AdminPanel\routing\interfaces\IRoute;
use vadimcontenthunter\AdminPanel\controllers\MainController;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeNone;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;

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
        $response = new ResponseTypeNone(false, 1, 'error: module name not found!');

        if (
            $parameters['modules']
            && $parameters['module_name']
            && $parameters['route']
            && is_array($parameters['modules'])
        ) {
            $module = Module::searchByName($parameters['modules'], $parameters['module_name']);
            if ($module instanceof Module) {
                $user = MainController::setAccessToUser(Helper::getCurrentHostUrl() . '/admin/login');
                $adminPageUi = MainController::getAdminPageUiFactory($user);
                $parameters['contentContainerUi'] = $adminPageUi->getContentComponent();
                $routing = $module->getRoutingForModule($parameters);
                $route = $routing->searchByPattern($parameters['route']);
                if ($route instanceof IRoute) {
                    $method_name = $route->getMethodName();
                    $result = $module->$method_name($parameters) ?? null;
                    if ($result instanceof AResponseType) {
                        $response = $result;
                    }
                }
            }
        }

        $renderResponse = new RenderResponse($response);
        $renderResponse->render();
    }
}
