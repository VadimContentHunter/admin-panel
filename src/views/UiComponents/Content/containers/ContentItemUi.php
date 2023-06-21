<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Content\containers;

use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\interfaces\IContentItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ContentItemUi implements IContentItemUi
{
    /**
     * @var string[]
     */
    protected array $html_scripts = [];

    protected string $content = '';

    protected int $gridColumnCount = 0;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        protected array $parameters,
        protected string $pathToTemplates = '',
        protected string $templateContainerName = 'UiComponents/content-container.php'
    ) {
    }

    public function getGridColumnCount(): int
    {
        return $this->gridColumnCount;
    }

    public function setGridColumnCount(int $count): ContentItemUi
    {
        $this->gridColumnCount = $count;
        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function addHtmlScript(string $template_file_name, array $parameters = []): ContentItemUi
    {
        extract($parameters);
        ob_start();
            include $this->pathToTemplates . '/scripts/' . $template_file_name;
            $js_content = ob_get_contents();
        ob_end_clean();

        if (!is_string($js_content)) {
            $js_content = '';
        }

        // if (!preg_match('~<script\s.*>.*</script>~u', $js_content)) {
        //     throw new AdminPanelException("Error, file not found.");
        // }

        $this->html_scripts[] = $js_content;
        return $this;
    }

    public function setContent(string $template_file_name): ContentItemUi
    {
        extract($this->parameters);
        ob_start();
            include $this->pathToTemplates . '/' . $template_file_name;
            $content = ob_get_contents();
        ob_end_clean();

        $this->content = $content ?: '';
        return $this;
    }

    public function getHtml(): string
    {
        $html_scripts = $this->html_scripts;
        $grid_column_count = $this->getGridColumnCount();
        $content = $this->content;

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateContainerName;
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
