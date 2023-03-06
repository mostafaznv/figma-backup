<?php

namespace App\DTOs;

class BackupResultData
{
    public bool $status;

    public function __construct(public readonly string $message = '')
    {
        $this->status = $message === '';
    }

    public static function make(string $message = ''): self
    {
        return new self($message);
    }
}
