<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\view;

use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockItemUi implements IContentItemUi
{
    /**
     * @var string[]
     */
    protected array $inputBlocks = [];

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        protected string $templateName,
        protected array $parameters = [],
        protected ?string $pathToTemplates = null,
    ) {
    }

    public function getGridColumnCount(): int
    {
        return -1;
    }

    public function addInput(string $label_text, string $type, string $name, string $value = ''): BlockItemUi
    {
        $label = '<label>' . $label_text . '</label>';
        $input = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" />';
        $this->inputBlocks[] = $label . $input;
        return $this;
    }

    public function getHtml(): string
    {
        $input_blocks = $this->inputBlocks;
        extract($this->parameters);
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

    public function getPathToTemplates(): ?string
    {
        return $this->pathToTemplates;
    }
}
