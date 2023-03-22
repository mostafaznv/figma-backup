<?php

namespace App\Console\Commands;

use App\Actions\BackupAction;
use App\Actions\SendTelegramMessageAction;
use App\Models\Project;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class FigmaBackupCommand extends Command
{
    protected $signature   = 'figma:backup';
    protected $description = "Download figma backup files";

    public function __construct(
        private readonly BackupAction              $backup,
        private readonly SendTelegramMessageAction $telegram
    ) {
        parent::__construct();
    }


    public function handle(): int
    {
        $this->telegram->info()->send();

        Project::query()->chunk(100, function($projects) {
            foreach ($projects as $project) {
                $this->line($project->name);

                $res = $this->backup->run($project);

                $res->status
                    ? $this->info('✔️ Done')
                    : $this->error('❌ ' . $res->message);
            }
        });

        return SymfonyCommand::SUCCESS;
    }
}
