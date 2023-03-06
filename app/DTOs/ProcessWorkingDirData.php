<?php

namespace App\DTOs;

class ProcessWorkingDirData
{
    public function __construct(
        public readonly string $disk,
        public readonly string $path = ''
    ) {}

    public static function make(string $disk, string $path = ''): self
    {
        return new self($disk, $path);
    }
}
