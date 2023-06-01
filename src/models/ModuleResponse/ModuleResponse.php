<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\ModuleResponse;

use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcError;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;
use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleResponse implements IModuleResponse
{
    protected ?IJsonRpcResponse $response = null;

    public function __construct(
        protected int|null $id = null,
    ) {
        $this->response = new JsonRpcResponse(error: new JsonRpcError(1, 'Неизвестная ошибка!.'), id: $this->id);
    }

    public function setResponseError(?IJsonRpcError $error): self
    {
        $this->response = new JsonRpcResponse(error: $error, id: $this->id);
        return $this;
    }

    public function setNotResponse(): self
    {
        $this->response = null;
        return $this;
    }

    public function setResponseLocation(string $path): self
    {
        $this->response = new JsonRpcResponse(['location' => $path], $this->id);
        return $this;
    }

    public function setResponseNotification(
        string $content,
        string $title = 'Уведомление',
        \DateTimeImmutable $data = new \DateTimeImmutable()
    ): self {
        $data = [
            'notification' => [
                'title' => $title,
                'data_time' => $data->format('d.m.Y H:i'),
                'content' => $content,
            ]
        ];
        $this->response = new JsonRpcResponse($data, $this->id);
        return $this;
    }

    public function setResponseHtml(string $html): self
    {
        $this->response = new JsonRpcResponse($html, $this->id);
        return $this;
    }

    /**
     * @param string[] $path_js_files
     */
    public function setResponseHtmlAndJsFiles(string $html, array $path_js_files): self
    {
        $data = [
            'html' => $html,
            'pathJsFiles' => $path_js_files
        ];
        $this->response = new JsonRpcResponse($data, $this->id);
        return $this;
    }

    /**
     * @param string[] $path_js_files
     */
    public function setResponseHtmlAndJsFromFiles(string $html, array $path_js_files): self
    {
        $pack_js = [];

        foreach ($path_js_files as $key => $path) {
            $jsString = file_get_contents($path);
            if ($jsString === false) {
                continue;
            }
            $pack_js[] = $jsString;
        }

        $data = [
            'html' => $html,
            'js' => $pack_js
        ];
        $this->response = new JsonRpcResponse($data, $this->id);
        return $this;
    }

    public function getResponse(): ?IJsonRpcResponse
    {
        return $this->response;
    }
}
