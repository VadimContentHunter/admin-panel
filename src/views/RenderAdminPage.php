<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\templates\UiComponents\AdminPageFactories\interfaces\IAdminPageUiFactory;

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
    protected array $jsHeadPaths = [];

    /**
     * @var string[]
     */
    protected array $jsBeginBodyPaths = [];

    /**
     * @var string[]
     */
    protected array $jsAfterBodyPaths = [];

    public function __construct(
        protected IAdminPageUiFactory $adminPageUiFactory
    ) {
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
        if (file_exists($path_file) && $this->checkFileExtension($path_file, 'css')) {
            throw new AdminPanelException("Error, file not found or not css.");
        }
        $this->cssPaths[] = $path_file;
        return $this;
    }

    public function addJsFileHead(string $path_file): RenderAdminPage
    {
        if (file_exists($path_file) && $this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsHeadPaths[] = $path_file;
        return $this;
    }

    public function addJsFileBeginBody(string $path_file): RenderAdminPage
    {
        if (file_exists($path_file) && $this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsBeginBodyPaths[] = $path_file;
        return $this;
    }

    public function addJsFileAfterBody(string $path_file): RenderAdminPage
    {
        if (file_exists($path_file) && $this->checkFileExtension($path_file, 'js')) {
            throw new AdminPanelException("Error, file not found or not js.");
        }
        $this->jsAfterBodyPaths[] = $path_file;
        return $this;
    }
}
