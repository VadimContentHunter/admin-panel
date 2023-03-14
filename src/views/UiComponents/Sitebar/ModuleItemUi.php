<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleItemUi implements IModuleItemUi
{
    public function __construct(
        protected string $title,
        protected string $moduleName,
        protected string $iconPath = 'icon-module',
        protected bool $activated = false,
        protected ?string $url = null
    ) {
    }

    public function getRequestContent(): string
    {
        return $this->url ?? (Helper::getCurrentHostUrl() . 'admin/module/' . $this->moduleName);
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
