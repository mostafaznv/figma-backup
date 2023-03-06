<?php

namespace App\Actions;

use App\DTOs\ProcessWorkingDirData;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

final class ProcessAction
{
    private readonly string $workingDirectory;

    private const TIMEOUT = 3600;

    public function __construct(
        private readonly ProcessWorkingDirData $workingDir,
        private readonly int                   $timeout = self::TIMEOUT
    ) {}

    public function run(array $cmd): Process
    {
        $process = new Process($cmd);

        $process->setWorkingDirectory($this->workingDir());
        $process->setTimeout($this->timeout);
        $process->setIdleTimeout($this->timeout);
        $process->run();

        return $process;
    }

    private function workingDir(): string
    {
        if (!isset($this->workingDirectory)) {
            $disk = $this->workingDir->disk;
            $path = $this->workingDir->path;

            Storage::disk($disk)->makeDirectory($path);

            $this->workingDirectory = Storage::disk($disk)->path($path);
        }

        return $this->workingDirectory;
    }
}
