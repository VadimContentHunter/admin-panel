<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IAccountItemUi extends IBaseUiComponent
{
    public function setTitle(string $title): IAccountItemUi;

    public function setValue(string $url): IAccountItemUi;

    public function setPathToTemplates(string $url): IAccountItemUi;
}
