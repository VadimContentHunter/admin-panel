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
    public function setTitle(string $title): IHeaderUi;

    public function setDate(string $date): IHeaderUi;

    public function setContent(string $content): IHeaderUi;

    public function hasPush(bool $flag = false): IHeaderUi;
}
