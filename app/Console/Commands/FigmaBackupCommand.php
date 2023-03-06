<?php

namespace App\Console\Commands;

use App\Notifications\WarningNotification;
use App\Notifications\StartBackupNotification;
use Exception;
use App\Models\Project;
use App\Models\ProjectBackup;
use App\Notifications\BackupNotification;
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
        $this->sendInfo();

        Project::query()->chunk(100, function($projects) {
            foreach ($projects as $project) {
                $this->line($project->name);

                try {
                    $this->backup($project);

                    $this->info('✔️ Done');
                }
                catch (Exception $e) {
                    $this->error($e->getMessage());

                    $this->warning($project->name, $e->getMessage(), $project->hashtag);
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
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $pathName = $this->prepareFileName($project->name, $fileName, $file->getExtension());
                $path = "$project->slug/$pathName";

                $stored = $this->storeFigFile($path, $file);

                if ($stored) {
                    /**
                     * @var ProjectBackup $backup
                     */
                    $backup = $project->backups()->create([
                        'name' => Str::title($fileName),
                        'path' => $path,
                        'size' => $file->getSize()
                    ]);

                    $this->notify($backup);
                }
            }

            $this->cleanup();
        }
        else {
            $this->error($process->getErrorOutput());
            $this->warning($project->name, $process->getErrorOutput(), $project->hashtag);
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

    private function prepareFileName(string $projectName, string $fileName, string $extension): string
    {
        return pascalCase($projectName) . '-' . pascalCase($fileName) . '-' . now()->unix() . '.' . $extension;

    }

    private function storeFigFile(string $path, UploadedFile $file): bool
    {
        return Storage::disk(self::BACKUP_DISK)->put($path, $file->getContent());
    }

    private function cleanup(): void
    {
        $directories = Storage::disk(self::FIGMA_DISK)->allDirectories('figma-backup-root/backups');

        foreach ($directories as $directory) {
            Storage::disk(self::FIGMA_DISK)->deleteDirectory($directory);
        }
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
            $isJamFile = Str::endsWith($file, '.jam');

            if ($isFigFile or $isJamFile) {
                $path = Storage::disk(self::FIGMA_DISK)->path($file);
                $figFiles[] = new UploadedFile($path, basename($path));
            }
        }

        return $figFiles;
    }

    private function sendInfo(): void
    {
        Notification::send($this->telegramIds, new StartBackupNotification());
    }

    private function notify(ProjectBackup $backup): void
    {
        Notification::send($this->telegramIds, new BackupNotification($backup));
    }

    private function warning(string $title, string $message, ?string $hashtag = null): void
    {
        Notification::send($this->telegramIds, new WarningNotification($title, $message, $hashtag));
    }
}
