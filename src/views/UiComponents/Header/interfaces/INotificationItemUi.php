<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface INotificationItemUi extends IBaseUiComponent
{
    public function setTitle(string $title): INotificationItemUi;

    public function setDate(string $date): INotificationItemUi;

    public function setContent(string $content): INotificationItemUi;

    public function hasPush(bool $flag = false): INotificationItemUi;
}
