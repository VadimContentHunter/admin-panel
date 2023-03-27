<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\INotificationItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class NotificationItemUi implements INotificationItemUi
{
    public function __construct(
        protected string $title = '',
        protected string $date = '',
        protected string $content = '',
        protected bool $statusPush = false,
        protected string $pathToTemplates = '',
        protected string $templateName = 'UiComponents/header-notification-menu-item-ui.php'
    ) {
    }

    public function setTitle(string $title): INotificationItemUi
    {
        $this->title = $title;
        return $this;
    }

    public function setDate(string $date): INotificationItemUi
    {
        $this->date = $date;
        return $this;
    }

    public function setContent(string $content): INotificationItemUi
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusPush(bool $flag = false): INotificationItemUi
    {
        $this->statusPush = $flag;
        return $this;
    }

    public function getHtml(): string
    {
        $item_title = $this->title;
        $item_date = $this->date;
        $item_content = $this->content;
        $has_push = $this->statusPush;

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
