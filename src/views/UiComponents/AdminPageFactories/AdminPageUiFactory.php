<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories;

use vadimcontenthunter\AdminPanel\views\UiComponents\Header\HeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\SitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
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
    protected IHeaderUi $headerUi;

    protected ISitebarUi $sidebarUi;

    protected IContentContainerUi $contentUi;

    public function __construct(
        string $user_name,
        string $user_icon_path,
        string $site_logo_path,
        string $content_title,
        protected string $templatesPath,
    ) {
        $this->headerUi = new HeaderUi($user_name, $user_icon_path);
        $this->sidebarUi = new SitebarUi($site_logo_path);
        $this->contentUi = new ContentContainerUi($content_title, $templatesPath);
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
