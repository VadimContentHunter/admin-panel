<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\exceptions;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class AdminPanelException extends \Exception
{
    public function __construct(
        string $message = "AdminPanelException",
        protected LoggerInterface $loggerInterface = new NullLogger(),
        protected string $logLevel = LogLevel::ERROR,
        int $code = 0
    ) {
        $this->message = $message;
        $this->code = $code;
        $this->loggerInterface->log($logLevel, $message);
    }
}
