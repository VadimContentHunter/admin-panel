<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\configs;

use vadimcontenthunter\AdminPanel\services\Helper;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AdminPanelSetting
{
    public static string $dbName = '';

    public static string $dbHost = '';

    public static string $dbUser = '';

    public static string $dbPassword = '';

    public static string $dbType = '';

    public static function getModuleUrl(string $module_name): string
    {
        return Helper::getCurrentHostUrl() . '/admin/module/' . $module_name;
    }

    public static function getPathModuleUrl(string $module_name): string
    {
        return Helper::getCurrentHostUrl() . '/src/modules/' . $module_name;
    }

    public static function getPathToResources(?string $resource_name = null): string
    {
        return Helper::getCurrentHostUrl() . '/src/resources' . ($resource_name !== null ? '/' . $resource_name : '');
    }

    public static function getPathToTemplates(?string $template_name = null): string
    {
        return __DIR__ . '\..\templates' . ($template_name !== null ? '/' . $template_name : '');
    }

    public static function getPathToTemplatesForModules(string $module_name, string $template_name = null): string
    {
        return __DIR__ . '/../modules/' . $module_name . '/templates/' . $template_name;
    }

    public static function getPathToModule(string $module_name): string
    {
        return __DIR__ . '/../modules/' . $module_name;
    }
}
