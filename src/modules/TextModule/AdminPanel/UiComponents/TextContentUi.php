<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\TextModule\AdminPanel\UiComponents;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TextContentUi implements IContentItemUi
{
    public function __construct(
        protected string $title,
        protected string $description = '',
        protected string $pathToTemplates = '',
        protected string $templateName = 'text-container-content-ui.php'
    ) {
    }

    public function getGridColumnCount(): int
    {
        return 5;
    }

    public function getHtml(): string
    {
        $title = $this->title;
        $grid_column_count = $this->getGridColumnCount();

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }

    public function setPathToTemplates(string $path_to_templates): IContentItemUi
    {
        $this->pathToTemplates = $path_to_templates;
        return $this;
    }
}
