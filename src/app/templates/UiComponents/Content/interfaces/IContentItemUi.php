<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\templates\UiComponents\Content\interfaces;

use vadimcontenthunter\AdminPane\templates\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IContentItemUi extends IBaseUiComponent
{
    public function getGridColumnCount(): int;
}
