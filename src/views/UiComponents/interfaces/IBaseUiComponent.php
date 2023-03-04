<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IBaseUiComponent
{
    public function getHtml(): string;
}
