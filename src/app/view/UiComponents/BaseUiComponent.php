<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents;

use vadimcontenthunter\AdminPane\view\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BaseUiComponent implements IBaseUiComponent
{
    public function __construct(
        protected string $htmlCode
    ) {
    }

    public function getHtml(): string
    {
        return $this->htmlCode;
    }
}
