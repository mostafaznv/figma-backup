<?php

namespace App\Actions;

use App\Models\ProjectBackup;
use App\Notifications\BackupNotification;
use App\Notifications\GenericNotification;
use App\Notifications\StartBackupNotification;
use App\Notifications\WarningNotification;
use Illuminate\Support\Facades\Notification;

final class SendTelegramMessageAction
{
    private readonly array $telegramIds;

    public function __construct()
    {
        $this->telegramIds = config('settings.telegram-to');
    }


    public function generic(string $title, string $content): void
    {
        Notification::send($this->telegramIds, new GenericNotification($title, $content));
    }

    public function info(): void
    {
        Notification::send($this->telegramIds, new StartBackupNotification());
    }

    public function notify(ProjectBackup $backup): void
    {
        Notification::send($this->telegramIds, new BackupNotification($backup));
    }

    public function warning(string $title, string $message, ?string $hashtag = null): void
    {
        Notification::send($this->telegramIds, new WarningNotification($title, $message, $hashtag));
    }
}
