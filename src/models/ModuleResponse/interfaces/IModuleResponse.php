<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\ModuleResponse\interfaces;

use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IModuleResponse
{
    public function setResponseLocation(string $path): self;

    public function setResponseHtml(string $html): self;

    public function getResponse(): ?IJsonRpcResponse;
}
