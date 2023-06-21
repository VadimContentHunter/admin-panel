<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\views\RenderResponse;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
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
        $module_url_data = $parameters['module_url_data'] ?? '';
        $module_name = $parameters['module_name'] ?? null;
        if ($module_name === null) {
            exit('Module name not found.');
        }

        $module_method = $this->getMethodFromRestUrl($module_url_data);
        $module = Module::searchByName($parameters['modules'], $parameters['module_name']);
        if ($module instanceof Module) {
            if (method_exists($module, $module_method)) {
                // $path_to_block = $module->getPathModule();
                $parameters['path_to_block'] = $this->getBlockPathFromRestUrl($module_url_data);
                echo $module->$module_method($parameters);
                exit();
            } else {
                exit('method not found.');
            }
        }
        exit('another error occurred.');
    }

    public function getBlockPathFromRestUrl(string $full_url_parameter): string
    {
        $path_to_block = '';
        $module_url_data = array_filter(preg_split('~(/|\\\)~', $full_url_parameter) ?: [] );
        $hasBlockPath = false;
        foreach ($module_url_data as $key => $url_chunk) {
            if ($hasBlockPath) {
                $path_to_block .= $url_chunk . '/';
            }

            if ($url_chunk === 'block') {
                $hasBlockPath = true;
            }
        }

        $path_to_block = substr($path_to_block, 0, -1);
        return $path_to_block . '.php';
    }

    public function getMethodFromRestUrl(string $full_url_parameter): string
    {
        $method_name = '';
        $module_url_data = preg_split('~(/|\\\)~', $full_url_parameter) ?: [];
        $hasBlockPath = false;
        foreach ($module_url_data as $key => $url_chunk) {
            if ($url_chunk === 'block') {
                $hasBlockPath = true;
            }
            if ($url_chunk !== 'block' && !$hasBlockPath) {
                $method_name = $url_chunk;
            }
        }

        return $method_name;
    }
}
