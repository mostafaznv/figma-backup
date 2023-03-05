<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly ?string $hashtag = null
    ) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        $datetime = now()->toDateTimeString();

        return TelegramMessage::create()
            ->to($notifiable)
            ->content("*Warning* [$this->title]")
            ->line($this->message)
            ->line('')
            ->line("*Datetime:* $datetime")
            ->line($this->hashtag);
    }
}
