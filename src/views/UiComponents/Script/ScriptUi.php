<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Sitebar\interfaces\IScriptsUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ScriptUi implements IScriptsUi
{
    public function __construct(
        protected string $templateName,
        protected string $pathToTemplates = '',
    ) {
    }

    public function getHtml(): string
    {
        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
