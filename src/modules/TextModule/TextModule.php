<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\TextModule;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\Module\Module;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
use vadimcontenthunter\AdminPanel\modules\TextModule\AdminPanel\UiComponents\TextContentUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TextModule extends Module
{
    public function getAdminContentUi(): IContentContainerUi
    {
        $contentContainer = new ContentContainerUi('', $this->getPathModule() . '/AdminPanel/templates');
        $contentContainer->addContent(
            (new TextContentUi('Dashboard'))
        );

        return $contentContainer;
    }
}
