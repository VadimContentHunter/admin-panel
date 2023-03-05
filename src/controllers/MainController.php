<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\views\RenderAdminPage;
use vadimcontenthunter\AdminPanel\services\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\HeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\SitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\AdminPageUiFactory;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MainController
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
        $page_title = 'Admin panel';
        $user_name = $parameters['user_name'] ?? 'Test';
        $user_icon_path = AdminPanelSetting::getPathToResources('img/profile.png');
        $logo_path = 'icon-panel';
        $content_title = 'None';

        $headerUi = new HeaderUi($user_name, $user_icon_path);
        $sitebarUi = new SitebarUi($logo_path);
        $contentContainer = new ContentContainerUi($content_title);

        $this->settingSiteBarUi($sitebarUi);
        $this->settingContentContainer($contentContainer);

        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'));
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/admin-panel/style.css'));
        $this->renderAdminPage->renderPageUiComponent(
            'admin-panel-page.php',
            (new AdminPageUiFactory($headerUi, $sitebarUi, $contentContainer)),
            ['page_title' => $page_title]
        );
    }

    protected function settingContentContainer(ContentContainerUi $contentContainer): void
    {
        $contentContainer->addContent(
            (new ContentItemUi('Test Content'))
        );
    }

    protected function settingSiteBarUi(SitebarUi $sitebarUi): void
    {
        $sitebarUi->addMenuMainItem(
            (new MainItemUi('Dashboard', Helper::getCurrentHostUrl(), activated: true))
        )
        ->addMenuMainItem(
            (new MainItemUi('Управление пользователями', Helper::getCurrentHostUrl()))
        )
        ->addMenuMainItem(
            (new MainItemUi('Настройки доступа', Helper::getCurrentHostUrl()))
        );

        $sitebarUi->addMenuModuleItem(
            (new ModuleItemUi('Module 1', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 2', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 3', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 4', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 5', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 6', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 7', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 8', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 9', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 10', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 11', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 12', Helper::getCurrentHostUrl()))
        )
        ->addMenuModuleItem(
            (new ModuleItemUi('Module 13', Helper::getCurrentHostUrl()))
        );
    }
}
