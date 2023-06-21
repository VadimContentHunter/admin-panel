<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Responses;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\IResponse;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Response implements IResponse
{
    protected bool $success = false;

    protected string $message = '';

    protected string $type = 'none';

    protected int $code = 1;

    /**
     * @var mixed[]
     */
    public array $data = [];

    public function setSuccess(bool $success): IResponse
    {
        $this->success = $success;
        return $this;
    }

    public function setMessage(string $message): IResponse
    {
        $this->message = $message;
        return $this;
    }

    public function setType(string $type): IResponse
    {
        $this->type = $type;
        return $this;
    }

    public function setCode(int $code): IResponse
    {
        $this->code = $code;
        return $this;
    }

    /**
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): IResponse
    {
        $this->data = $data;
        return $this;
    }

    /**
     *
     * @param array<string, mixed> $data
     */
    public function addData(array $data): IResponse
    {
        $this->data += $data;
        return $this;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getDataJson(): string
    {
        $json = json_encode($this->getData());
        if (!is_string($json)) {
            throw new AdminPanelException("Error, unable to convert request data to json format.");
        }
        return $json;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getJsonFormat(): string
    {
        $object = new \stdClass();
        $object->success = $this->getSuccess();
        $object->message = $this->getMessage();
        $object->code = $this->getCode();
        $object->data = $this->getDataJson();
        $object->type = $this->getType();

        $json = json_encode($object);
        if (!is_string($json)) {
            throw new AdminPanelException("Error, unable to convert request object to json format.");
        }
        return $json;
    }
}
