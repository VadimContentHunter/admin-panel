<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents\Sitebar;

use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MainItemUi implements IBaseUiComponent
{
    public function __construct(
        protected string $iconPath = 'icon-module',
        protected string $title,
        protected string $requestContent,
        protected bool $activated = false,
    ) {
    }

    private function getStartBlockHtml(): string
    {
        return $this->activated ? '<li class="activated" >' : '<li>';
    }

    private function getIconHtml(): string
    {
        return '<div class="' . $this->iconPath . '"><i></i></div>';
    }

    public function getHtml(): string
    {
        return <<<HTML
            {$this->getStartBlockHtml()}
                {$this->getIconHtml()}
                <a>{$this->title}</a>
            </li>
        HTML;
    }
}
