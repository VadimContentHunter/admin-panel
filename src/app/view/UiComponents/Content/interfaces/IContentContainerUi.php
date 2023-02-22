<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents\Content\interfaces;

use vadimcontenthunter\AdminPane\view\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\view\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IContentContainerUi extends IBaseUiComponent
{
    public function addContent(IContentItemUi $content_item): IContentContainerUi;
}
