<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Content;

use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ContentContainerUi implements IContentContainerUi
{
    /**
     * @var array<IContentItemUi>
     */
    private array $contentItems = [];

    public function __construct(
        protected string $templatesPath,
        protected string $title = '',
    ) {
    }

    public function getHtml(): string
    {
        return <<<HTML
        <div class="items-container">
            {$this->getContentItemsHtml()}
        <div>
        HTML;
    }

    public function addContent(IContentItemUi $content_item): IContentContainerUi
    {
        $content_item->setPathToTemplates($this->templatesPath);
        $this->contentItems[] = $content_item;
        return $this;
    }

    private function getContentItemsHtml(): string
    {
        return implode(array_map(
            fn (IContentItemUi $objContentItemUi) => $objContentItemUi->getHtml(),
            $this->contentItems
        ));
    }

    public function setTitle(string $title): IContentContainerUi
    {
        $this->title = $title;
        return $this;
    }
}
