<?php

namespace App\Console;

use App\Console\Commands\DeleteOldFilesCommand;
use App\Console\Commands\FigmaBackupCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cache:prune-stale-tags')->hourly();
        $schedule->command(FigmaBackupCommand::class)->daily();
        $schedule->command(DeleteOldFilesCommand::class)->dailyAt('9:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
