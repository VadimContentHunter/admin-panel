<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\interfaces;

use vadimcontenthunter\AdminPane\view\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IContentContainerUi
{
    public function addContent(IContentItemUi $content_item): IContentContainerUi;
}
