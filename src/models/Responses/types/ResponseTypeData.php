<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Responses\types;

use vadimcontenthunter\AdminPanel\models\Responses\Response;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\IResponse;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ResponseTypeData extends AResponseType
{
    /**
     * @param mixed[] $data
     */
    public function __construct(bool $success, int $code, array $data = [], ?string $message = null)
    {
        $this->response = new Response();
        $this->response->setSuccess($success);
        $this->response->setMessage($message ?? '');
        $this->response->setCode($code);
        $this->response->setData($data);
        $this->response->setType($this->getType());
    }

    /**
     * @param mixed[] $data
     */
    public function setData(array $data): ResponseTypeData
    {
        $this->response->setData($data);
        return $this;
    }

    public function getResponse(): IResponse
    {
        return $this->response;
    }

    public function getType(): string
    {
        return 'data';
    }
}
