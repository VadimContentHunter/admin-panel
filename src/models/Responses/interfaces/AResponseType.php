<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Responses\interfaces;

use vadimcontenthunter\AdminPanel\models\Responses\interfaces\IResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class AResponseType
{
    protected IResponse $response;

    protected string $type = 'none';

    abstract public function getResponse(): IResponse;

    abstract public function getType(): string;

    public function setSuccess(bool $success): AResponseType
    {
        $this->response->setSuccess($success);
        return $this;
    }

    public function setMessage(string $message): AResponseType
    {
        $this->response->setMessage($message);
        return $this;
    }

    public function setCode(int $code): AResponseType
    {
        $this->response->setCode($code);
        return $this;
    }
}
