<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPane\view\UiComponents;

use vadimcontenthunter\AdminPane\view\interfaces\IContentItemUi;
use vadimcontenthunter\AdminPane\view\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPane\view\interfaces\IContentContainerUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ContentContainerUi implements IBaseUiComponent, IContentContainerUi
{
    /**
     * @var array<IContentItemUi>
     */
    private array $contentItems = [];

    public function __construct(
        protected string $title
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
        $this->contentItems[] = $content_item;
        return $this;
    }

    private function getContentItemsHtml(): string
    {
        return implode(array_map(
            fn (IContentItemUi $objContentItemUi) => $objContentItemUi->getGridColumnCount(),
            $this->contentItems
        ));
    }
}
