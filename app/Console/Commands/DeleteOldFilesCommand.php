<?php

namespace App\Console\Commands;

use App\Actions\SendTelegramMessageAction;
use App\Consts\Disks;
use App\Models\ProjectBackup;
use App\Models\QueryBuilders\ProjectBackupQueryBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class DeleteOldFilesCommand extends Command
{
    protected $signature   = 'figma:delete-old-files';
    protected $description = 'Delete old backup files';

    private int $total = 0;

    public function __construct(private readonly SendTelegramMessageAction $telegram)
    {
        parent::__construct();
    }


    public function handle(): int
    {
        $this->oldBackups()->chunk(100, function($backups) {
            foreach ($backups as $backup) {
                $this->line($backup->project->name);

                $deleted = $backup->path && Storage::disk(Disks::BACKUP)->delete($backup->path);

                if ($deleted) {
                    $backup->path = null;
                    $backup->save();

                    $this->total++;
                    $this->info('✔️ Deleted');
                }
                else {
                    $this->warn('❌ Failed to delete');
                }
            }
        });

        if ($this->total) {
            $this->telegram
                ->generic(title: 'Old files deleted', content: "$this->total old backup files deleted")
                ->send();
        }

        return SymfonyCommand::SUCCESS;
    }

    private function oldBackups(): ProjectBackupQueryBuilder
    {
        return ProjectBackup::whereIsOnExpireDay()->with('project');
    }
}
