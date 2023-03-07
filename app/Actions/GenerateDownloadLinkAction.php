<?php

namespace App\Actions;

use App\Models\ProjectBackup;
use App\Traits\HashId;
use Illuminate\Support\Facades\URL;

final class GenerateDownloadLinkAction
{
    use HashId;

    const TTL = 12;

    public function __construct(private readonly ProjectBackup $backup) {}

    public function __invoke(bool $signed = false): ?string
    {
        if ($this->backup->path) {
            $backupId = $this->backup->id;
            $hashId = $this->hashids()->encode($backupId);

            if ($signed) {
                $ttl = now()->addHours(self::TTL);

                return URL::temporarySignedRoute('download', $ttl, [
                        'id'   => $backupId,
                        'hash' => $hashId,
                        'date' => now()->unix()
                    ]
                );
            }

            return route('projects.download', [
                'any_project' => $backupId,
                'id'          => $backupId,
                'hash'        => $hashId,
            ]);
        }

        return null;
    }


}
