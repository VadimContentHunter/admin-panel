<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IHeaderUi extends IBaseUiComponent
{
    public function addControlMenuItem(IControlItemUi $controlItem): IHeaderUi;

    public function addAccountMenuItem(IAccountItemUi $accountItem): IHeaderUi;

    public function addNotificationMenuItem(INotificationItemUi $notificationItem): IHeaderUi;
}
