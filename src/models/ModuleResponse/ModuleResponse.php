<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\ModuleResponse;

use vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces\IModuleResponse;
use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleResponse implements IModuleResponse
{
    protected IJsonRpcResponse $response;

    public function __construct(
        protected int|null $id = null,
    ) {
        $this->response = new JsonRpcResponse(error: new JsonRpcError(1, 'Неизвестная ошибка!.'), id: $this->id);
    }

    public function setResponseLocation(string $path): self
    {
        $this->response = new JsonRpcResponse(['location' => $path], $this->id);
        return $this;
    }

    public function setResponseHtml(string $html): self
    {
        $this->response = new JsonRpcResponse($html, $this->id);
        return $this;
    }

    public function setResponseHtmlAndJs(string $html, string $path_js_file): self
    {
        $data = [
            'html' => $html,
            'pathJsFile' => $path_js_file
        ];
        $this->response = new JsonRpcResponse($data, $this->id);
        return $this;
    }

    public function getResponse(): IJsonRpcResponse
    {
        return $this->response;
    }
}
