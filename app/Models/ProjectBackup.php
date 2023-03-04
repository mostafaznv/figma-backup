<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectBackup extends Model
{
    protected $fillable = [
        'project_id', 'name', 'path', 'size'
    ];

    protected function link(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Storage::disk('backups')->url($attributes['path'])
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
