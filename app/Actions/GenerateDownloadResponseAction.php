<?php

namespace App\Actions;

use App\Models\ProjectBackup;
use App\Models\Scopes\ActiveScope;
use App\Traits\HashId;
use Illuminate\Support\Facades\Storage;
use Mostafaznv\PhpXsendfile\Facades\PhpXsendfile;

final class GenerateDownloadResponseAction
{
    use HashId;

    public function __construct(
        private readonly IncrementBackupTotalDownloadsAction $incrementTotalDownloads
    ) {}

    public function __invoke(int $backupId, string $hashId): mixed
    {
        $hashIds = $this->hashids()->decode($hashId);

        if ($backupId == $hashIds[0]) {
            $backup = $this->backup($backupId);
            $path = Storage::disk('backups')->path($backup->path);

            ($this->incrementTotalDownloads)($backup);

            PhpXsendfile::download($path);
        }
    }

    private function backup(int $id): ProjectBackup
    {
        /**
         * @var ProjectBackup $backup
         */
        $backup = ProjectBackup::query()
            ->withoutGlobalScope(ActiveScope::class)
            ->where('id', $id)
            ->first();

        return $backup;
    }
}
