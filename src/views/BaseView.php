<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BaseView
{
    public function __construct(
        protected string $templatesPath,
        protected ?string $contentType = null,
        protected ?string $templatesPathToErrorPage = null
    ) {
    }

    public function setContentType(string $type): self
    {
        $this->contentType = $type;
        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function renderPage(string $template_name, array $parameters = [], int $code = 200): void
    {
        http_response_code($code);
        if ($this->contentType !== null) {
            header('Content-type: ' . $this->contentType);
        }
        echo $this->getPage($template_name, $parameters);
    }

    /**
     * @param mixed[] $parameters
     */
    public function getPage(string $template_name, array $parameters = []): string
    {
        extract($parameters);
        ob_start();
            include $this->templatesPath . '/' . $template_name;
            $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer ?: '';
    }
}
