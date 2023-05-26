<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\configs\AdminPanelSetting;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IMainItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IModuleItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MainItemUi implements IMainItemUi
{
    public function __construct(
        protected string $title,
        protected string $moduleName,
        protected string $iconPath = 'icon-module',
        protected bool $activated = false,
        protected string $pathToTemplates = '',
        protected string $templateName = 'UiComponents/sitebar-menu-item.php',
        protected ?string $valueData = null
    ) {
    }

    public function setActivateMenuItem(bool $flag): IModuleItemUi
    {
        $this->activated = $flag;
        return $this;
    }

    public function setPathToTemplates(string $path_to_templates): IModuleItemUi
    {
        $this->pathToTemplates = $path_to_templates;
        return $this;
    }

    public function getRequestContent(): string
    {
        return $this->valueData ?? 'content';
    }

    /**
     * @throws AdminPanelException
     */
    public function getHtml(): string
    {
        $item_class = $this->iconPath;
        $item_text = $this->title;
        $item_activated = $this->activated;
        $value_data = $this->getRequestContent();

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $html = ob_get_contents();
        ob_end_clean();

        return $html ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
