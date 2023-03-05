<?php

namespace App\Models;

use App\Observers\ProjectBackupObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectBackup extends Model
{
    protected $fillable = [
        'project_id', 'name', 'path', 'size'
    ];

    public static function booted()
    {
        ProjectBackup::observe(ProjectBackupObserver::class);
    }

    protected function link(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Storage::disk('backups')->url($attributes['path'])
        );
    }

    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Storage::disk('backups')->path($attributes['path'])
        );
    }

    protected function isLarge(): Attribute
    {
        $bytesInMb = config('settings.bytes-in-mb');
        $limitation = 49 * $bytesInMb;

        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['size'] > $limitation
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
