<?php

namespace App\Models;

use App\Actions\GenerateDownloadLinkAction;
use App\Consts\Cache;
use App\Enums\FileType;
use App\Models\QueryBuilders\ProjectBackupQueryBuilder;
use App\Observers\ProjectBackupObserver;
use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Mostafaznv\LaraCache\CacheEntity;
use Mostafaznv\LaraCache\Traits\LaraCache;

/**
 * @method static ProjectBackupQueryBuilder whereExpired(?int $expiryDays = null)
 * @method static ProjectBackupQueryBuilder whereIsOnExpireDay(?int $expiryDays = null)
 */
class ProjectBackup extends Model
{
    use HasHashId, LaraCache;

    protected $fillable = [
        'project_id', 'name', 'path', 'size'
    ];

    protected $casts = [
        'type' => FileType::class
    ];

    public static function booted()
    {
        ProjectBackup::observe(ProjectBackupObserver::class);
    }

    public function newEloquentBuilder($query): ProjectBackupQueryBuilder
    {
        return new ProjectBackupQueryBuilder($query);
    }

    public static function cacheEntities(): array
    {
        return [
            CacheEntity::make(Cache::TOTAL_DOWNLOADS)
                ->cache(function() {
                    return ProjectBackup::query()->sum('total_downloads');
                }),

            CacheEntity::make(Cache::TOTAL_BACKUPS)
                ->cache(function() {
                    return ProjectBackup::query()->count();
                }),

            CacheEntity::make(Cache::TOTAL_AVAILABLE_BACKUPS)
                ->cache(function() {
                    return ProjectBackup::query()->whereNotNull('path')->count();
                })
        ];
    }


    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => Storage::disk('backups')->path($attributes['path'])
        );
    }

    protected function isLarge(): Attribute
    {
        $bytesInMb = config('settings.bytes-in-mb');
        $limitation = config('settings.telegram-max-file-size') * $bytesInMb;

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['size'] > $limitation
        );
    }

    public function link(bool $signed = false): ?string
    {
        $action = new GenerateDownloadLinkAction($this);

        return $action($signed);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
