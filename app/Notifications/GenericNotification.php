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

        return TelegramMessage::create()
            ->to($notifiable)
            ->line("*$this->title*")
            ->content("$this->content")
            ->line('')
            ->line("$datetime")
            ->disableNotification();
    }
}
