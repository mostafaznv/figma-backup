<?php

namespace App\Observers;

use App\Models\ProjectBackup;

class ProjectBackupObserver
{
    public function created(ProjectBackup $backup): void
    {
        $backup->project?->update([
            'latest_backup_at' => $backup->created_at
        ]);
    }
}
