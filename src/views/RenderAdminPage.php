<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Content\ContentContainerUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\AdminPageFactories\interfaces\IAdminPageUiFactory;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class RenderAdminPage
{
    /**
     * @var string[]
     */
    protected array $cssPaths = [];

    /**
     * @var string[]
     */
    protected array $headCodeFiles = [];

    /**
     * @var string[]
     */
    protected array $jsHeadPaths = [];

    /**
     * @var string[]
     */
    protected array $jsBeginBodyPaths = [];

    /**
     * @var string[]
     */
    protected array $jsAfterBodyPaths = [];

    /**
     * @var string[]
     */
    protected array $html_scripts_after_body = [];

    public function __construct(
        protected string $templatesPath,
        protected string $templateHeadName,
    ) {
    }

    /**
     * @param  array<string, mixed> $parameters
     * @throws AdminPanelException
     */
    public function renderPageUiComponent(string $template_name, IAdminPageUiFactory $adminPageUiFactory, array $parameters = [], int $code = 200): void
    {
        http_response_code($code);
        extract($parameters);

        $sidebar = $adminPageUiFactory->getSidebarComponent()->getHtml();
        $header = $adminPageUiFactory->getHeaderComponent()->getHtml();
        $body_container = $adminPageUiFactory->getContentComponent()->getHtml();
        $css_paths = $this->cssPaths;
        $head_code_paths = $this->headCodeFiles;
        $js_head_paths = $this->jsHeadPaths;
        $js_begin_body_paths = $this->jsBeginBodyPaths;
        $js_after_body_paths = $this->jsAfterBodyPaths;
        $html_scripts_after_body = $this->html_scripts_after_body;

        ob_start();
            include $this->templatesPath . '/' . $this->templateHeadName;
            $head = ob_get_contents();
        ob_end_clean();

        ob_start();
            include $this->templatesPath . '/' . $template_name;
            $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }

    /**
     * @param  array<string, mixed> $parameters
     * @throws AdminPanelException
     */
    public function render(string $template_name, array $parameters = [], int $code = 200): void
    {
        http_response_code($code);
        extract($parameters);

        $css_paths = $this->cssPaths;
        $head_code_paths = $this->headCodeFiles;
        $js_head_paths = $this->jsHeadPaths;
        $js_begin_body_paths = $this->jsBeginBodyPaths;
        $js_after_body_paths = $this->jsAfterBodyPaths;
        $html_scripts_after_body = $this->html_scripts_after_body;

        ob_start();
            include $this->templatesPath . '/' . $this->templateHeadName;
            $head = ob_get_contents();
        ob_end_clean();

        ob_start();
            include $this->templatesPath . '/' . $template_name;
            $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }

    protected function checkFileExtension(string $path_file, string $extension): bool
    {
        if (preg_match('~\.' . $extension . '~u', $path_file)) {
            return true;
        }
        return false;
    }

    public function addCssFile(string $path_file): RenderAdminPage
    {
        if (/*!file_exists($path_file) ||*/ !$this->checkFileExtension($path_file, 'css')) {
            throw new AdminPanelException("Error, file not found or not css.");
        }
        $this->cssPaths[] = $path_file;
        return $this;
    }

    public function addHeadCodeFiles(string $code_block): RenderAdminPage
    {
        $this->headCodeFiles[] = $code_block;
        return $this;
    }

    public function addJsFileHead(string $path_file): RenderAdminPage
    {
        if (/*!file_exists($path_file) ||*/ !$this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsHeadPaths[] = $path_file;
        return $this;
    }

    public function addJsFileBeginBody(string $path_file): RenderAdminPage
    {
        if (/*!file_exists($path_file) ||*/ !$this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsBeginBodyPaths[] = $path_file;
        return $this;
    }

    public function addJsFileAfterBody(string $path_file): RenderAdminPage
    {
        if (/*!file_exists($path_file) ||*/ !$this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsAfterBodyPaths[] = $path_file;
        return $this;
    }

    public function addHtmlScript(string $template_file_name): RenderAdminPage
    {
        ob_start();
            include $this->templatesPath . '/scripts/' . $template_file_name;
            $js_content = ob_get_contents();
        ob_end_clean();

        if (!is_string($js_content)) {
            $js_content = '';
        }

        // if (!preg_match('~<script\s.*>.*</script>~u', $js_content)) {
        //     throw new AdminPanelException("Error, file not found.");
        // }

        $this->html_scripts_after_body[] = $js_content;
        return $this;
    }
}
