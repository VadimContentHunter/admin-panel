<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\templates\UiComponents\Content;

use vadimcontenthunter\AdminPane\templates\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ContentItemUi implements IContentItemUi
{
    public function __construct(
        protected string $title,
        protected string $description = ''
    ) {
    }

    public function getGridColumnCount(): int
    {
        return 3;
    }

    public function getHtml(): string
    {
        return <<<HTML
            <section class="container-base-style container-one">
                <div class="panel-header">
                    <h4>Личные данные</h4>
                    <a class="icon-panel">
                        <i></i>
                    </a>
                </div>
                <form>
                    <ul>
                        <li>
                            <label >Имя пользователя</label>
                            <input type="text" name="login" placeholder="Введите имя пользователя" value="Вадим Б." readonly>
                            <output name="result_name">Некорректно указан логин.</output>
                        </li>
                        <li>
                            <label >Электронная почта</label>
                            <input type="email" name="email" placeholder="Введите электронную почту" value="vadim.test.1@mail.com" readonly>
                            <output name="result_name">Неверно указанная почта.</output>
                        </li>
                        <li>
                            <label >Пароль</label>
                            <input type="password" name="password" placeholder="Введите пароль" value="************" readonly>
                            <output name="result_name">Некорректно указан пароль.</output>
                        </li>
                        <li class="hidden">
                            <label >Повторите пароль</label>
                            <input type="password" name="confirm_password" placeholder="Повторите пароль">
                            <output name="result_name">Пароли не совпадают.</output>
                        </li>
                    </ul>
                    <div class="panel-footer">
                        <button type="button">Сохранить</button>
                    </div>
                </form>
            </section>
        HTML;
    }
}
