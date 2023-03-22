<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $content
    ) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        $datetime = now()->toFormattedDayDateString();
        $content = maskSensitiveData($this->content);

        return TelegramMessage::create()
            ->to($notifiable)
            ->content("*$this->title*")
            ->line('')
            ->line($content)
            ->line('')
            ->line("$datetime")
            ->disableNotification();
    }
}
