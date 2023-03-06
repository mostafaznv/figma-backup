<?php

namespace App\Actions;

use App\Consts\Disks;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FigmaStorageAction
{
    public function files(): array
    {
        $files = Storage::disk(Disks::FIGMA)->allFiles('figma-backup-root/backups');
        $figFiles = [];

        foreach ($files as $file) {
            $isFigFile = Str::endsWith($file, '.fig');
            $isJamFile = Str::endsWith($file, '.jam');

            if ($isFigFile or $isJamFile) {
                $path = Storage::disk(Disks::FIGMA)->path($file);
                $figFiles[] = new UploadedFile($path, basename($path));
            }
        }

        return $figFiles;
    }

    public function store(string $path, UploadedFile $file): bool
    {
        return Storage::disk(Disks::BACKUP)->put($path, $file->getContent());
    }

    public function cleanup(): void
    {
        $directories = Storage::disk(Disks::FIGMA)->allDirectories('figma-backup-root/backups');

        foreach ($directories as $directory) {
            Storage::disk(Disks::FIGMA)->deleteDirectory($directory);
        }
    }

    public function prepareFileName(string $projectName, string $fileName, string $extension): string
    {
        return pascalCase($projectName) . '-' . pascalCase($fileName) . '-' . now()->unix() . '.' . $extension;
    }
}
