<?php

namespace App\Actions;

use Exception;
use App\Models\ProjectBackup;
use App\Notifications\BackupNotification;
use App\Notifications\GenericNotification;
use App\Notifications\StartBackupNotification;
use App\Notifications\WarningNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as LaravelNotification;

final class SendTelegramMessageAction
{
    private readonly array $telegramBackupsIds;
    private readonly array $telegramWarningIds;

    /**
     * @var LaravelNotification[]
     */
    private array $bag = [];

    private const DELAY = 4;


    public function __construct()
    {
        $sendToIds = config('settings.telegram-to');

        $this->telegramBackupsIds = $sendToIds['backups'];
        $this->telegramWarningIds = $sendToIds['warnings'];
    }


    public function generic(string $title, string $content): self
    {
        $this->queue(
            new GenericNotification($title, $content)
        );

        return $this;
    }

    public function info(): self
    {
        $this->queue(
            new StartBackupNotification()
        );

        return $this;
    }

    public function notify(ProjectBackup $backup): self
    {
        $this->queue(
            new BackupNotification($backup)
        );

        return $this;
    }

    public function warning(string $title, string $message, ?string $hashtag = null): self
    {
        $this->queue(
            new WarningNotification($title, $message, $hashtag)
        );

        return $this;
    }

    public function send(int $delay = null): array
    {
        $delay = is_null($delay) ? self::DELAY : $delay;
        $errors = [];

        foreach ($this->bag as $index => $notification) {
            try {
                if ($index) {
                    sleep($delay);
                }

                $isInstanceOfWarning = $notification instanceof WarningNotification;

                Notification::send(
                    notifiables: $isInstanceOfWarning ? $this->telegramWarningIds : $this->telegramBackupsIds,
                    notification: $notification
                );
            }
            catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        $this->bag = [];

        return $errors;
    }

    private function queue(LaravelNotification $notification): void
    {
        $this->bag[] = $notification;
    }
}
