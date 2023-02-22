<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\templates\UiComponents\Content\interfaces;

use vadimcontenthunter\AdminPane\templates\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\templates\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IContentContainerUi extends IBaseUiComponent
{
    public function addContent(IContentItemUi $content_item): IContentContainerUi;
}
