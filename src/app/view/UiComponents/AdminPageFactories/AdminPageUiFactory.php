<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents\AdminPageFactories;

use vadimcontenthunter\AdminPane\view\UiComponents\Header\interfaces\IHeaderUi;
use vadimcontenthunter\AdminPane\view\UiComponents\Sitebar\interfaces\ISitebarUi;
use vadimcontenthunter\AdminPane\view\UiComponents\Content\interfaces\IContentContainerUi;
use vadimcontenthunter\AdminPane\view\UiComponents\AdminPageFactories\interfaces\IAdminPageUiFactory;

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

    public function setContentComponent(IContentContainerUi $contentUi): IAdminPageUiFactory
    {
        $this->contentUi = $contentUi;
        return $this;
    }

    public function setHeaderComponent(IHeaderUi $objHeaderUi): IAdminPageUiFactory
    {
        $this->headerUi = $objHeaderUi;
        return $this;
    }

    public function setSidebarComponent(ISitebarUi $sidebarUi): IAdminPageUiFactory
    {
        $this->sidebarUi = $sidebarUi;
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
