<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\views\BaseView;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\views\RenderAdminPage;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;

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
        $baseView = new BaseView(AdminPanelSetting::getPathToTemplates());
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'));
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/login-page/login-page.css'));
        $this->renderAdminPage->addJsFileHead(AdminPanelSetting::getPathToResources('js/MainLibrary/MainLibrary.js'));
        $this->renderAdminPage->addJsFileAfterBody(AdminPanelSetting::getPathToResources('js/LoginForm.js'));
        $this->renderAdminPage->addJsFileAfterBody(AdminPanelSetting::getPathToResources('js/SwitchingPanelsLogin.js'));
        $this->renderAdminPage->addHeadCodeFiles($baseView->getPage('scripts/init_paths.php', [
            'path_node_modules' => Helper::getCurrentHostUrl(),
            'path_resources_js' => Helper::getCurrentHostUrl() . '/src/resources/js',
            'path_module' => Helper::getCurrentHostUrl() . '/src/modules',
        ]));
        $this->renderAdminPage->render(
            'login-page.php',
            [
                'page_title' => 'login',
                'login_action' => Helper::getCurrentHostUrl() . '/admin/users',
                'login_json_rpc_method' => 'loginUser',
                'sing_in_action' => Helper::getCurrentHostUrl() . '/admin/users',
                'sing_in_json_rpc_method' => 'registerUser',
            ]
        );
    }
}
