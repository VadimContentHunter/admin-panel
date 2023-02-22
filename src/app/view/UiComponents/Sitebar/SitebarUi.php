<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents\Sitebar;

use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\ISitebarUi;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class SitebarUi implements IBaseUiComponent, ISitebarUi
{
    /**
     * @var array<IMainItemUi>
     */
    protected array $mainItems;

    /**
     * @var array<IModuleItemUi>
     */
    protected array $moduleItems;

    public function __construct(
        protected string $logoPath,
    ) {
    }

    private function getMainItemsHtml(): string
    {
        return implode(array_map(
            fn (IMainItemUi $objMainItemUi) => $objMainItemUi->getHtml(),
            $this->mainItems
        ));
    }

    private function getModuleItemsHtml(): string
    {
        return implode(array_map(
            fn (IMainItemUi $objModuleItemUi) => $objModuleItemUi->getHtml(),
            $this->moduleItems
        ));
    }

    private function getIconBlockHtml(): string
    {
        return <<<HTML
            <div class="logo">
                <div class="{$this->logoPath}">
                    <i></i>
                </div>
                <h1>
                    Admin-panel
                </h1>
            </div>
        HTML;
    }

    /**
     * @return array<IBaseUiComponent>
     */
    public function setLogo(string $logo_path): ISitebarUi
    {
        $this->logoPath = $logo_path;
        return $this;
    }

    /**
     * @return ISitebarUi
     */
    public function addMenuMainItem(IMainItemUi $main_item): ISitebarUi
    {
        $this->mainItems[] = $main_item;
        return $this;
    }

    /**
     *
     * @return ISitebarUi
     */
    public function addMenuModuleItem(IModuleItemUi $module_item): ISitebarUi
    {
        $this->moduleItems[] = $module_item;
        return $this;
    }

    public function getHtml(): string
    {
        return <<<HTML
            <section class="sidebar">

                {$this->getIconBlockHtml()}
                <section class="main-items">
                    <h6>Основное</h6>

                    <menu>
                        {$this->getMainItemsHtml()}
                    </menu>
                </section>

                <section class="modules-items">
                    <h6>Модули</h6>

                    <menu>
                        {$this->getModuleItemsHtml()}
                    </menu>
                </section>

            </section>
        HTML;
    }
}
