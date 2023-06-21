<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\views;

use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\models\Responses\interfaces\AResponseType;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class RenderResponse
{
    public function __construct(
        protected AResponseType $responseType
    ) {
    }

    public function render(): void
    {
        echo $this->responseType->getResponse()->getJsonFormat();
    }
}
