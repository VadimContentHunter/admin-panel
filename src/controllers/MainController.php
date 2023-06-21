<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\controllers\UserController;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeHtml;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\views\RenderAdminPage;
use vadimcontenthunter\AdminPanel\views\RenderResponse;
use vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\AdminPageUiFactory;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers\TextContentUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\AccountItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\NotificationItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\ISitebarUi;

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
        $this->user = $this::setAccessToUser(Helper::getCurrentHostUrl() . '/admin/login');

        $adminPageUi = $this->getAdminPageUiFactory($this->user);

        $this->settingModule($parameters, $adminPageUi->getSidebarComponent(), $adminPageUi->getContentComponent());
        // $this->settingSiteBarUi($adminPageUi->getSidebarComponent());
        // $this->settingContentContainer($adminPageUi->getContentComponent());

        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/eric-meyers-css-reset.css'));
        $this->renderAdminPage->addCssFile(AdminPanelSetting::getPathToResources('css/admin-panel/style.css'));
        $this->renderAdminPage->addJsFileHead(AdminPanelSetting::getPathToResources('js/MainLibrary/MainLibrary.js'));
        $this->renderAdminPage->addJsFileHead(AdminPanelSetting::getPathToResources('js/Notification/Notification.js'));
        $this->renderAdminPage->addJsFileAfterBody(AdminPanelSetting::getPathToResources('js/ControlMenuItem.js'));
        $this->renderAdminPage->renderPageUiComponent(
            'admin-panel-page.php',
            $adminPageUi,
            ['page_title' => 'Admin Panel']
        );
    }

    public static function setAccessToUser(string $url): IUser
    {
        $user = UserController::getUserObjBySession();
        if ($user instanceof IUser) {
            return $user;
        }

        header('Location: ' . $url);
        exit;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    protected function settingModule(array $parameters, ISitebarUi $sitebarUi, IContentContainerUi $contentContainer): void
    {
        $is_first_elem = true;
        $temp_menu_item = null;
        $temp_module = null;
        if ($parameters['modules'] && is_array($parameters['modules'])) {
            foreach ($parameters['modules'] as $key => $module) {
                if ($module instanceof Module) {
                    $menuItem = $module->getMenuItem();
                    if ($module->getName() === 'TextModule') {
                        if ($is_first_elem) {
                            $menuItem->setActivateMenuItem(true);
                            $module->builderAdminContentUi($contentContainer);
                            $is_first_elem = false;
                        }

                        if ($menuItem instanceof IMainItemUi) {
                            $sitebarUi->addMenuMainItem($menuItem);
                        } elseif ($menuItem instanceof IModuleItemUi) {
                            $sitebarUi->addMenuModuleItem($menuItem);
                        }
                    } elseif ($module->getName() === 'ExitModule') {
                        continue;
                    } elseif ($module->getName() === 'UserAccountModule') {
                        continue;
                    } else {
                        $temp_menu_item = $menuItem;
                        $temp_module = $module;

                        $sitebarUi->addMenuModuleItem($menuItem);
                    }
                }
            }
        }

        if ($is_first_elem && $temp_module !== null && $temp_menu_item !== null) {
            $temp_menu_item->setActivateMenuItem(true);
            $temp_module->builderAdminContentUi($contentContainer);
        }
    }

    public static function getAdminPageUiFactory(IUser $user): AdminPageUiFactory
    {
        $adminPageUi = new AdminPageUiFactory(
            $user->getName(),
            AdminPanelSetting::getPathToResources('img/profile.png'),
            'icon-panel',
            AdminPanelSetting::getPathToTemplates(),
            'None',
        );

        // Настройка меню пользователя
        $adminPageUi->getHeaderComponent()->addAccountMenuItem(new AccountItemUi(
            'Аккаунт',
            'UserAccountModule|getContent'
        ));

        $adminPageUi->getHeaderComponent()->addAccountMenuItem(new AccountItemUi(
            'Выход',
            'ExitModule|signOut'
        ));

        // Настройка Уведомлений
        $adminPageUi->getHeaderComponent()->addNotificationMenuItem(new NotificationItemUi(
            content: 'Уведомлений, нет.',
            statusDefault: true
        ));

        return $adminPageUi;
    }
}
