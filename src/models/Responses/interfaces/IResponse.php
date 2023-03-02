<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Responses\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IResponse
{
    public function setSuccess(bool $success): IResponse;

    public function setMessage(string $message): IResponse;

    public function setType(string $type): IResponse;

    public function setCode(int $code): IResponse;

    /**
     * @param mixed[] $data
     */
    public function setData(array $data): IResponse;

    public function getSuccess(): bool;

    /**
     * @return mixed[]
     */
    public function getData(): array;

    public function getCode(): int;

    public function getType(): string;

    public function getMessage(): string;

    public function getJsonFormat(): string;
}
