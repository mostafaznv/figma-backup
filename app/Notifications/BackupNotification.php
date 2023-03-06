<?php

namespace App\Notifications;

use App\Models\ProjectBackup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class BackupNotification extends Notification implements ShouldQueue
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
        $type = $this->backup->type->name;
        $size = byteToMb($this->backup->size);
        $datetime = $this->backup->created_at->format('Y-m-d H:i:s');
        $hashtag = $this->backup->project->hashtag;

        if ($this->backup->is_large) {
            return TelegramMessage::create()
                ->to($notifiable)
                ->content("*$projectName*")
                ->line('')
                ->line('')
                ->line("*Type:* {$type}")
                ->line("*Size:* {$size}MB")
                ->line("*Datetime:* $datetime")
                ->line('')
                ->line("[Download]({$this->backup->link})")
                ->line($hashtag)
                ->disableNotification();
        }
        else {
            $content = <<<MARKDOWN
            *$projectName*

            *Type:* $type
            *Size:* {$size}MB
            *Datetime:* $datetime

            $hashtag
            MARKDOWN;


            return TelegramFile::create()
                ->to($notifiable)
                ->content($content)
                ->document($this->backup->full_path, $this->backup->name)
                ->disableNotification();
        }
    }
}
