<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\templates\UiComponents\Sitebar;

use vadimcontenthunter\AdminPanel\templates\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleItemUi implements IModuleItemUi
{
    public function __construct(
        protected string $title,
        protected string $requestContent,
        protected string $iconPath = 'icon-module',
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