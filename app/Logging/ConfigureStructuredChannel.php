<?php

namespace App\Logging;

use Illuminate\Log\Logger;

class ConfigureStructuredChannel
{
    /**
     * Customize the given logger instance with sanitizer and structured JSON formatter.
     */
    public function __invoke(Logger $logger): void
    {
        $monolog = $logger->getLogger();

        if (method_exists($monolog, 'pushProcessor')) {
            $monolog->pushProcessor(new SanitizeSensitiveDataProcessor());
        }

        $formatter = new StructuredJsonFormatter();

        foreach ($monolog->getHandlers() as $handler) {
            if (method_exists($handler, 'setFormatter')) {
                $handler->setFormatter($formatter);
            }
        }
    }
}

