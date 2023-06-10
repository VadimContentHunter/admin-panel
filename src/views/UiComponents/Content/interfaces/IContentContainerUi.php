<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IContentContainerUi extends IBaseUiComponent
{
    public function setTitle(string $title): IContentContainerUi;
    public function addContent(IContentItemUi $content_item): IContentContainerUi;
    public function setTemplate(string $path): IContentContainerUi;
}
