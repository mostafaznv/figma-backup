<?php

namespace App\Models;

use App\Traits\Active;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use Active;

    protected $fillable = [
        'figma_id', 'name', 'slug', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function backups(): HasMany
    {
        return $this->hasMany(ProjectBackup::class);
    }
}
