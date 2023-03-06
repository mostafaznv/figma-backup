<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\Scopes\ActiveScope;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class StartBackupNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        $datetime = now()->toFormattedDayDateString();
        $active = Project::query()->count();
        $total = Project::query()->withoutGlobalScope(ActiveScope::class)->count();
        $projects = Project::query()
            ->select('slug')
            ->get()
            ->map(function($row) {
                return "`$row->slug`";
            })
            ->implode(', ');

        return TelegramMessage::create()
            ->to($notifiable)
            ->content("*$datetime*")
            ->line('')
            ->line('')
            ->line("*Total Projects:* $total")
            ->line("*Active Projects:* $active")
            ->line("------------")
            ->line($projects);
    }
}
