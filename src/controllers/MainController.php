<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\views\RenderAdminPage;
use vadimcontenthunter\AdminPanel\controllers\UserController;
use vadimcontenthunter\AdminPanel\services\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\HeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\SitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\MainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\ModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\ISitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\ContentItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\AdminPageUiFactory;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\DashboardContentUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MainController
{
    protected RenderAdminPage $renderAdminPage;

    protected IUser $user;

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
        $this->setAccessToUser(Helper::getCurrentHostUrl() . '/admin/login');

        $adminPageUi = new AdminPageUiFactory(
            $this->user->getName(),
            AdminPanelSetting::getPathToResources('img/profile.png'),
            'icon-panel',
            AdminPanelSetting::getPathToTemplates(),
            'None',
        );

        $this->settingModule($parameters, $adminPageUi->getSidebarComponent(), $adminPageUi->getContentComponent());
        // $this->settingSiteBarUi($adminPageUi->getSidebarComponent());
        // $this->settingContentContainer($adminPageUi->getContentComponent());

        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'));
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/admin-panel/style.css'));
        $this->renderAdminPage->renderPageUiComponent(
            'admin-panel-page.php',
            $adminPageUi,
            ['page_title' => 'Admin Panel']
        );
    }

    protected function setAccessToUser(string $url): void
    {
        $temp = UserController::getUserObjBySession();
        if ($temp instanceof IUser) {
            $this->user = $temp;
            return;
        }

        header('Location: ' . $url);
        exit;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    protected function settingModule(array $parameters, ISitebarUi $sitebarUi, IContentContainerUi $contentContainer): void
    {
        if ($parameters['modules'] && is_array($parameters['modules'])) {
            foreach ($parameters['modules'] as $key => $module) {
                if ($module instanceof Module) {
                    if ($module->getTitle() === 'TextModule') {
                        $menuItem = $module->getMenuItem();

                        if ($menuItem instanceof IMainItemUi) {
                            $sitebarUi->addMenuMainItem($menuItem);
                        } elseif ($menuItem instanceof IModuleItemUi) {
                            $sitebarUi->addMenuModuleItem($menuItem);
                        }

                        $module->builderAdminContentUi($contentContainer);
                    }
                }
            }
        }

        $sitebarUi->activateFirstItemMenu();
    }

    protected function settingContentContainer(IContentContainerUi $contentContainer): void
    {
        // $contentContainer->addContent(
        //     (new DashboardContentUi('Dashboard'))
        // );
    }

    protected function settingSiteBarUi(ISitebarUi $sitebarUi): void
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
