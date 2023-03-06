<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

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

    public static string $dbType = 'mysql';

    public static function getPathToResources(?string $resource_name = null): string
    {
        return Helper::getCurrentHostUrl() . '/src/resources' . ($resource_name !== null ? '/' . $resource_name : '');
    }

    public static function getPathToTemplates(?string $template_name = null): string
    {
        $t = __DIR__ . '\..\templates' . ($template_name !== null ? '/' . $template_name : '');
        return __DIR__ . '\..\templates' . ($template_name !== null ? '/' . $template_name : '');
    }
}