<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\templates\UiComponents\Header;

use vadimcontenthunter\AdminPanel\templates\UiComponents\Header\interfaces\IHeaderUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class HeaderUi implements IHeaderUi
{
    public function __construct(
        protected string $userName,
        protected string $userIconPath
    ) {
    }

    private function getControlPanelHtml(): string
    {
        return <<<HTML
            <section class="control-panel">
            </section>
        HTML;
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

    private function getUserIconHtml(): string
    {
        return '<img src="' . $this->userIconPath . '" alt="profile">';
    }

    public function getHtml(): string
    {
        return <<<HTML
            <header>
                {$this->getControlPanelHtml()}
                <section class="account-control">
                    <div class="notification-block __push">
                        <div class="notifications" value="55">
                            <div class="icon-header-notification">
                                <i></i>
                            </div>
                        </div>
                        <div class="sub-menu">
                            {$this->getNotificationItemsHtml()}
                        </div>
                    </div>
                    <div class="account-block">
                        <div class="account-block-main">
                            {$this->getUserIconHtml()}
                            <div class="account-name">
                                <span>{$this->userName}</span>
                                <div class="icon-triangle">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        <div class="sub-menu">
                            <menu>
                                {$this->getAccountMenuItemsHtml()}
                            </menu>
                        </div>
                    </div>
                </section>
            </header>
        HTML;
    }
}
