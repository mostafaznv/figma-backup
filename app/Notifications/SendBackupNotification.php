<?php

namespace App\Notifications;

use App\Models\ProjectBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
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
        $projectName = $this->backup->project->name;
        $size = byteToMb($this->backup->size);
        $datetime = $this->backup->created_at->format('Y-m-d H:i:s');

        if ($this->backup->is_large) {
            return TelegramMessage::create()
                ->to($notifiable)
                ->content("*$projectName*")
                ->line("*Size:* {$size}MB")
                ->line("*Datetime:* $datetime")
                ->button('Download', $this->backup->link)
                ->disableNotification();
        }
        else {
            $content = <<<MARKDOWN
            *$projectName*
            *Size:* {$size}MB
            *Datetime:* $datetime
            MARKDOWN;


            return TelegramFile::create()
                ->to($notifiable)
                ->content($content)
                ->document($this->backup->full_path, $this->backup->name)
                ->disableNotification();
        }
    }
}
