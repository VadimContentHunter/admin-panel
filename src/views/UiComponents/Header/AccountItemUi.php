<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views\UiComponents\Header;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IHeaderUi;
use vadimcontenthunter\AdminPanel\views\UiComponents\Header\interfaces\IAccountItemUi;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AccountItemUi implements IAccountItemUi
{
    public function __construct(
        protected string $title = '',
        protected string $requestUrl = '',
        protected string $pathToTemplates = '',
        protected string $templateName = 'UiComponents/header-account-menu-item-ui.php'
    ) {
    }

    public function setTitle(string $title): IAccountItemUi
    {
        $this->title = $title;
        return $this;
    }

    public function setRequestUrl(string $url): IAccountItemUi
    {
        $this->requestUrl = $url;
        return $this;
    }

    public function setPathToTemplates(string $url): IAccountItemUi
    {
        $this->pathToTemplates = $url;
        return $this;
    }

    public function getHtml(): string
    {
        $item_text = $this->title;
        $request_url = $this->requestUrl;

        ob_start();
            include $this->pathToTemplates . '/' . $this->templateName;
            $template = ob_get_contents();
        ob_end_clean();

        return $template ?: throw new AdminPanelException('Error, unable to write template.');
    }
}
