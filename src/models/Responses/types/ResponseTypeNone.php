<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\Responses\types;

use vadimcontenthunter\AdminPanel\models\Responses\Response;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\IResponse;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;
use vadimcontenthunter\AdminPanel\views\UiComponents\interfaces\IBaseUiComponent;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ResponseTypeNone extends AResponseType
{
    public function __construct(bool $success, int $code, ?string $message = null)
    {
        $this->response = new Response();
        $this->response->setSuccess($success);
        $this->response->setMessage($message ?? '');
        $this->response->setCode($code);
    }


    public function getResponse(): IResponse
    {
        return $this->response;
    }

    public function getType(): string
    {
        return 'html';
    }
}
