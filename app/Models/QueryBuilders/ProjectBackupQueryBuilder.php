<?php

namespace App\Models\QueryBuilders;

use App\Traits\HashId;
use Illuminate\Database\Eloquent\Builder;

final class ProjectBackupQueryBuilder extends Builder
{
    use HashId;

    public function whereHashId(string $hashId): self
    {
        $id = $this->hashids()->decode($hashId);

        return $this->where('id', $id);
    }

    public function whereExpired(?int $expiryDays = null): self
    {
        if (is_null($expiryDays)) {
            $expiryDays = config('settings.file-expiry-days');
        }

        $date = now()->subDays($expiryDays)->toDateString();

        return $this->whereHas('project')
            ->whereNotNull('path')
            ->whereDate('created_at', '<', $date);
    }

    public function whereIsOnExpireDay(?int $day = null): self
    {
        if (is_null($day)) {
            $day = config('settings.file-expiry-days');
        }

        $date = now()->subDays($day)->toDateString();

        return $this->whereHas('project')
            ->whereNotNull('path')
            ->whereDate('created_at', $date);
    }
}
