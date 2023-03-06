<?php

namespace App\Observers;

use App\Enums\FileType;
use App\Models\ProjectBackup;

class ProjectBackupObserver
{
    public function creating(ProjectBackup $backup): void
    {
        $extension = pathinfo($backup->path, PATHINFO_EXTENSION);

        $backup->type = match ($extension) {
            'fig'   => FileType::FIG,
            'jam'   => FileType::JAM,
            default => FileType::OTHER,
        };
    }

    public function created(ProjectBackup $backup): void
    {
        $backup->project?->update([
            'latest_backup_at' => $backup->created_at
        ]);
    }
}
