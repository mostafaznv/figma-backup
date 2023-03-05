<?php

namespace App\Console\Commands;

use App\Notifications\SendWarningNotification;
use Exception;
use App\Models\Project;
use App\Models\ProjectBackup;
use App\Notifications\SendBackupNotification;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Process\Process;

class FigmaBackupCommand extends Command
{
    protected $signature   = 'figma:backup';
    protected $description = "Download figma backup files";

    private readonly string $email;
    private readonly string $password;
    private readonly string $token;
    private readonly string $workingDirectory;
    private readonly array  $telegramIds;

    private const BACKUP_DISK = 'backups';
    private const FIGMA_DISK  = 'figma';
    private const TIMEOUT     = 3600;

    public function __construct()
    {
        parent::__construct();

        $service = config('services.figma');

        $this->email = $service['email'];
        $this->password = $service['password'];
        $this->token = $service['token'];
        $this->workingDirectory = $this->makeFigmaDirectory();
        $this->telegramIds = config('settings.telegram-to');
    }


    public function handle(): int
    {
        Project::query()->chunk(100, function($projects) {
            foreach ($projects as $project) {
                $this->line("Project: $project->name");

                try {
                    $this->backup($project);

                    $this->line('✔️ Done');
                }
                catch (Exception $e) {
                    $this->error($e->getMessage());

                    $this->warning($project->name, $e->getMessage());
                }
            }
        });


        return SymfonyCommand::SUCCESS;
    }

    private function backup(Project $project): void
    {
        $process = $this->cmd($project->figma_id);

        if ($process->isSuccessful()) {
            $files = $this->getFigFiles();

            foreach ($files as $file) {
                $name = $project->name . ' ' . now()->toDateTimeString() . '.' . $file->getExtension();
                $pathName = $project->slug . '[' . now()->unix() . '].' . $file->getExtension();
                $path = "$project->slug/$pathName";

                $stored = $this->storeFigFile($path, $file);

                if ($stored) {
                    /**
                     * @var ProjectBackup $backup
                     */
                    $backup = $project->backups()->create([
                        'name' => $name,
                        'path' => $path,
                        'size' => $file->getSize()
                    ]);

                    $this->notify($backup);
                }

                $this->deleteOldFigFile($file);
            }
        }
        else {
            $this->error($process->getErrorOutput());
            $this->warning($project->name, $process->getErrorOutput());
        }
    }

    private function cmd(int|string $projectId): Process
    {
        $process = new Process([
            'figma-backup',
            '-e', $this->email,
            '-p', $this->password,
            '-t', $this->token,
            '--projects-ids', $projectId
        ]);

        $process->setWorkingDirectory($this->workingDirectory);
        $process->setTimeout(self::TIMEOUT);
        $process->setIdleTimeout(self::TIMEOUT);
        $process->run();

        return $process;
    }

    private function makeFigmaDirectory(): string
    {
        Storage::disk(self::FIGMA_DISK)->makeDirectory('');

        return Storage::disk(self::FIGMA_DISK)->path('');
    }

    private function storeFigFile(string $path, UploadedFile $file): bool
    {
        return Storage::disk(self::BACKUP_DISK)->put($path, $file->getContent());
    }

    private function deleteOldFigFile(string $path): void
    {
        Storage::disk(self::FIGMA_DISK)->delete($path);
    }

    /**
     * @return UploadedFile[]
     */
    private function getFigFiles(): array
    {
        $files = Storage::disk(self::FIGMA_DISK)->allFiles('figma-backup-root/backups');
        $figFiles = [];

        foreach ($files as $file) {
            $isFigFile = Str::endsWith($file, '.fig');

            if ($isFigFile) {
                $path = Storage::disk(self::FIGMA_DISK)->path($file);
                $figFiles[] = new UploadedFile($path, basename($path));
            }
        }

        return $figFiles;
    }

    private function notify(ProjectBackup $backup): void
    {
        Notification::send($this->telegramIds, new SendBackupNotification($backup));
    }

    private function warning(string $title, string $message): void
    {
        Notification::send($this->telegramIds, new SendWarningNotification($title, $message));
    }
}
