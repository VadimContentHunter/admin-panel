<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\exceptions;

use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BlockException extends AdminPanelException
{
    public function __construct(
        string $message = "BlockException",
        protected LoggerInterface $loggerInterface = new NullLogger(),
        protected string $logLevel = LogLevel::ERROR,
        int $code = 0
    ) {
        $this->message = $message;
        $this->code = $code;
        $this->loggerInterface->log($logLevel, $message);
    }
}
