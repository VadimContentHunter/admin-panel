<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

use vadimcontenthunter\AdminPanel\services\Helper;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AdminPanelSetting
{
    public static string $dbName = 'db_admin_panel';

    public static string $dbHost = '127.0.0.1';

    public static string $dbUser = 'mytest';

    public static string $dbPassword = 'mytest';

    public static string $dbType = 'mysql';

    public static function getModuleUrl(string $module_name): string
    {
        return Helper::getCurrentHostUrl() . '/admin/module/' . $module_name;
    }

    public static function getPathToResources(?string $resource_name = null): string
    {
        return Helper::getCurrentHostUrl() . '/src/resources' . ($resource_name !== null ? '/' . $resource_name : '');
    }

    public static function getPathToTemplates(?string $template_name = null): string
    {
        return __DIR__ . '\..\templates' . ($template_name !== null ? '/' . $template_name : '');
    }

    public static function getPathToTemplatesForModules(?string $template_name = null): string
    {
        return __DIR__ . '\..\templates' . ($template_name !== null ? '/' . $template_name : '');
    }
}
