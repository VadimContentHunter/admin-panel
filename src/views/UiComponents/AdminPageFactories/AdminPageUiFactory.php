<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories;

use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IHeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\ISitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\interfaces\IAdminPageUiFactory;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AdminPageUiFactory implements IAdminPageUiFactory
{
    public function __construct(
        protected IHeaderUi $headerUi,
        protected ISitebarUi $sidebarUi,
        protected IContentContainerUi $contentUi
    ) {
    }

    public function setContentComponent(IContentContainerUi $objContentUi): IAdminPageUiFactory
    {
        $this->contentUi = $objContentUi;
        return $this;
    }

    public function setHeaderComponent(IHeaderUi $objHeaderUi): IAdminPageUiFactory
    {
        $this->headerUi = $objHeaderUi;
        return $this;
    }

    public function setSidebarComponent(ISitebarUi $objSidebarUi): IAdminPageUiFactory
    {
        $this->sidebarUi = $objSidebarUi;
        return $this;
    }

    public function getContentComponent(): IContentContainerUi
    {
        return $this->contentUi;
    }

    public function getSidebarComponent(): ISitebarUi
    {
        return $this->sidebarUi;
    }

    public function getHeaderComponent(): IHeaderUi
    {
        return $this->headerUi;
    }
}
