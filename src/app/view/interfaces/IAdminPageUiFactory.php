<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\interfaces;

use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IAdminPageUiFactory
{
    public function setContentComponent(): IContentContainerUi;

    public function setHeaderComponent(): IHeaderUi;

    public function setSidebarComponent(): ISitebarUi;

    public function getContentComponent(): IContentContainerUi;

    public function getSidebarComponent(): ISitebarUi;

    public function getHeaderComponent(): IHeaderUi;
}
