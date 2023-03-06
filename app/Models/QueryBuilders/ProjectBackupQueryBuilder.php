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
}
