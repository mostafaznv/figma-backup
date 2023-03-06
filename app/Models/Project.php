<?php

namespace App\Models;

use App\Traits\Active;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use Active;

    protected $fillable = [
        'figma_id', 'name', 'slug', 'is_active', 'latest_backup_at'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected function hashtag(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attrs) => '#' . pascalCase($attrs['slug'])
        );
    }

    public function backups(): HasMany
    {
        return $this->hasMany(ProjectBackup::class)->orderByDesc('id');
    }
}
