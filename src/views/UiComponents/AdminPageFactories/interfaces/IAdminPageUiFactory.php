<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\interfaces;

use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IHeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\ISitebarUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IAdminPageUiFactory
{
    public function setContentComponent(IContentContainerUi $objContentUi): IAdminPageUiFactory;

    public function setHeaderComponent(IHeaderUi $objHeaderUi): IAdminPageUiFactory;

    public function setSidebarComponent(ISitebarUi $objSidebarUi): IAdminPageUiFactory;

    public function getContentComponent(): IContentContainerUi;

    public function getSidebarComponent(): ISitebarUi;

    public function getHeaderComponent(): IHeaderUi;
}
