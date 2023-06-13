<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\view;

use vadimcontenthunter\AdminPanel\modules\BlockManagement\view\BlockItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ContentBlocksUi implements IContentItemUi
{
    /**
     * @var string[]
     */
    protected array $blocks = [];

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        protected string $title,
        protected string $pathToTemplates = '',
        protected string $templateName = ''
    ) {
    }

    public function getGridColumnCount(): int
    {
        return -1;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function addBlock(BlockItemUi $block): ContentBlocksUi
    {
        if ($block->getPathToTemplates() === null) {
            $block->setPathToTemplates($this->pathToTemplates);
        }
        $this->blocks[] = $block->getHtml();
        return $this;
    }

    public function getHtml(): string
    {
        $title = $this->title;
        $blocks = $this->blocks;
        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $html = ob_get_contents();
        ob_end_clean();
        return $html ?: '';
    }

    public function setPathToTemplates(string $path_to_templates): IContentItemUi
    {
        $this->pathToTemplates = $path_to_templates;
        return $this;
    }
}
