<?php

namespace App\Notifications;

use App\Models\ProjectBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendBackupNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly ProjectBackup $backup) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        $telegramId = config('settings.telegram-to');

        $projectName = $this->backup->project->name;
        $size = byteToMb($this->backup->size);
        $datetime = $this->backup->created_at->format('Y-m-d H:i:s');


        return TelegramMessage::create()
            ->to($telegramId)
            ->content("*$projectName*")
            ->line("*Size:* {$size}MB")
            ->line("*Datetime:* $datetime")
            ->disableNotification()
            ->file($this->backup->link, $this->backup->name);
    }
}
