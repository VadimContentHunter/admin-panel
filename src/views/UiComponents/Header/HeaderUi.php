<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IHeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IAccountItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IControlItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\INotificationItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class HeaderUi implements IHeaderUi
{
    /**
     * @var string[]
     */
    protected array $controlMenuItems = [];

    /**
     * @var string[]
     */
    protected array $accountMenuItems = [];

    /**
     * @var string[]
     */
    protected array $notificationItems = [];

    public function __construct(
        protected string $userName,
        protected string $userIconPath,
        protected string $pathToTemplates = '',
        protected string $templateName = 'UiComponents/header-ui.php'
    ) {
    }

    public function addControlMenuItem(IControlItemUi $controlItem): IHeaderUi
    {
        $controlItem->setPathToTemplates($this->pathToTemplates);
        $this->controlMenuItems[] = $controlItem->getHtml();
        return $this;
    }

    public function addAccountMenuItem(IAccountItemUi $accountItem): IHeaderUi
    {
        $accountItem->setPathToTemplates($this->pathToTemplates);
        $this->accountMenuItems[] = $accountItem->getHtml();
        return $this;
    }

    public function addNotificationMenuItem(INotificationItemUi $notificationItem): IHeaderUi
    {
        $notificationItem->setPathToTemplates($this->pathToTemplates);
        $this->notificationItems[] = $notificationItem->getHtml();
        return $this;
    }

    public function getHtml(): string
    {
        $html_control_panel_items = $this->controlMenuItems;
        $html_account_sub_menu_items = $this->accountMenuItems;
        $html_notification_sub_menu_items = $this->notificationItems;
        $user_icon_path = $this->userIconPath;
        $user_name = $this->userName;
        $has_push = false;

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
