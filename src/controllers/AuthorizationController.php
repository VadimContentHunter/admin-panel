<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\views\RenderAdminPage;
use vadimcontenthunter\AdminPanel\services\AdminPanelSetting;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AuthorizationController
{
    protected RenderAdminPage $renderAdminPage;

    public function __construct()
    {
        $this->renderAdminPage = new RenderAdminPage(
            AdminPanelSetting::getPathToTemplates(),
            'head.php'
        );
    }

    /**
     * @param mixed[] $parameters
     */
    public function view(array $parameters): void
    {
        $page_title = 'login';

        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'));
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/login-page/login-page.css'));
        $this->renderAdminPage->addJsFileHead(AdminPanelSetting::getPathToResources('js/main_functions.js'));
        $this->renderAdminPage->render(
            'login-page.php',
            ['page_title' => $page_title]
        );
    }
}