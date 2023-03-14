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

    public function setActivateMenuItem(bool $flag): ModuleItemUi
    {
        $this->activated = $flag;
        return $this;
    }

    public function getRequestContent(): string
    {
        return $this->url ?? (Helper::getCurrentHostUrl() . 'admin/module/' . $this->moduleName);
    }

    private function getClassActivated(): string
    {
        return $this->activated ? 'class="activated"' : '';
    }

    private function getIconHtml(): string
    {
        return '<div class="' . $this->iconPath . '"><i></i></div>';
    }

    public function getHtml(): string
    {
        return <<<HTML
            <li {$this->getClassActivated()}>
                {$this->getIconHtml()}
                <a>{$this->title}</a>
            </li>
        HTML;
    }
}
