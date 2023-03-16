<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\exceptions\ModuleConfig;

use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ModuleConfigException extends AdminPanelException
{
    public function __construct(
        string $message = "ModuleConfigException",
        protected LoggerInterface $loggerInterface = new NullLogger(),
        protected string $logLevel = LogLevel::ERROR,
        int $code = 0
    ) {
        $this->message = $message;
        $this->code = $code;
        $this->loggerInterface->log($logLevel, $message);
    }
}
