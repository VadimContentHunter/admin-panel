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

    private function getControlPanelHtml(): string
    {
        return <<<HTML
            <section class="control-panel">
            </section>
        HTML;
    }

    public function addControlMenuItem(IControlItemUi $controlItem): IHeaderUi
    {
        $this->controlMenuItems[] = $controlItem->getHtml();
        return $this;
    }

    public function addAccountMenuItem(IAccountItemUi $accountItem): IHeaderUi
    {
        $this->accountMenuItems[] = $accountItem->getHtml();
        return $this;
    }

    public function addNotificationMenuItem(INotificationItemUi $notificationItem): IHeaderUi
    {
        $this->notificationItems[] = $notificationItem->getHtml();
        return $this;
    }

    private function getNotificationItemsHtml(): string
    {
        return <<<HTML
            <article class="__push">
                <h4>
                    <p>
                        Уведомление
                        <time datetime="2023-02-09T07:21:00">09.02.2023 07:21</time>
                    </p>
                    <menu>
                        <button class="check">
                            <div class="icon-check">
                                <i></i>
                            </div>
                        </button>
                    </menu>
                </h4>
                <a>
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                </a>
            </article>
            <article>
                <h4>
                    <p>
                        Уведомление
                        <time datetime="2023-02-09T07:21:00">09.02.2023 07:21</time>
                    </p>
                    <menu>
                        <button class="check">
                            <div class="icon-check">
                                <i></i>
                            </div>
                        </button>
                    </menu>
                </h4>
                <a>
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                </a>
            </article>
            <article>
                <h4>
                    <p>
                        Уведомление
                        <time datetime="2023-02-09T07:21:00">09.02.2023 07:21</time>
                    </p>
                    <menu>
                        <button class="check">
                            <div class="icon-check">
                                <i></i>
                            </div>
                        </button>
                    </menu>
                </h4>
                <a>
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                </a>
            </article>
            <article>
                <h4>
                    <p>
                        Уведомление
                        <time datetime="2023-02-09T07:21:00">09.02.2023 07:21</time>
                    </p>
                    <menu>
                        <button class="check">
                            <div class="icon-check">
                                <i></i>
                            </div>
                        </button>
                    </menu>
                </h4>
                <a>
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                </a>
            </article>
            <article>
                <h4>
                    <p>
                        Уведомление
                        <time datetime="2023-02-09T07:21:00">09.02.2023 07:21</time>
                    </p>
                    <menu>
                        <button class="check">
                            <div class="icon-check">
                                <i></i>
                            </div>
                        </button>
                    </menu>
                </h4>
                <a>
                    В Настройках  можно выбрать приложения, которые будут отправлять уведомления,
                    изменить звук уведомлений, настроить уведомления с учетом геопозиции,
                    разрешить уведомления от госучреждений и настроить другие параметры.
                </a>
            </article>
        HTML;
    }

    private function getAccountMenuItemsHtml(): string
    {
        return <<<HTML
            <li>
                <a>Аккаунт</a>
            </li>
            <li>
                <a>Статистика</a>
            </li>
            <li>
                <a>Настройки</a>
            </li>
            <li>
                <a>Выход</a>
            </li>
        HTML;
    }

    public function getHtml(): string
    {
        $html_control_panel_items = implode($this->controlMenuItems);
        $html_account_sub_menu_items = implode($this->accountMenuItems);
        $html_notification_sub_menu_items = implode($this->notificationItems);
        $user_icon_path = $this->userIconPath;
        $user_name = $this->userName;

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
