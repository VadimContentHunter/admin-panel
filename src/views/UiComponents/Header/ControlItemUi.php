<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header;

use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IControlItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ControlItemUi implements IControlItemUi
{
    public function __construct(
        protected string $pathToTemplates = ''
    ) {
    }

    public function setPathToTemplates(string $url): ControlItemUi
    {
        $this->pathToTemplates = $url;
        return $this;
    }

    public function getHtml(): string
    {
        return '';
    }
}
