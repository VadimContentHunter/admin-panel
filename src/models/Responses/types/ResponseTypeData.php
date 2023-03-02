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
     * Summary of __construct
     * @param bool $success
     * @param string $message
     * @param int $code
     * @param array $data
     */
    public function __construct(bool $success, string $message, int $code, array $data)
    {
        $this->response = new Response();
        $this->response->setSuccess($success);
        $this->response->setMessage($message);
        $this->response->setCode($code);
        $this->response->setData($data);
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
