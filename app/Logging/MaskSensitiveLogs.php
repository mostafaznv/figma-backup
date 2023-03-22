<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\LogRecord;

class MaskSensitiveLogs
{
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor([$this, 'process']);
        }
    }

    public function process(LogRecord $record): LogRecord
    {
        return new LogRecord(
            $record->datetime,
            $record->channel,
            $record->level,
            maskSensitiveData($record->message),
            $record->context,
            $record->extra,
            $record->formatted,
        );
    }
}
