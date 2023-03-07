<?php

namespace App\Actions;

use App\Models\ProjectBackup;

final class IncrementBackupTotalDownloadsAction
{
    public function __invoke(ProjectBackup $backup): ProjectBackup
    {
        $backup->total_downloads += 1;
        $backup->save();

        return $backup;
    }
}
