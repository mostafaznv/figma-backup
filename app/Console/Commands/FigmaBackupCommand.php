<?php

namespace App\Console\Commands;

use App\DTOs\FigFile;
use App\Models\Project;
use App\Models\ProjectBackup;
use App\Notifications\SendBackupNotification;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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

    private const BACKUP_DISK = 'backups';
    private const FIGMA_DISK  = 'backups';
    private const TIMEOUT     = 3600;

    public function __construct()
    {
        parent::__construct();

        $service = config('services.figma');

        $this->email = $service['email'];
        $this->password = $service['password'];
        $this->token = $service['token'];
        $this->workingDirectory = $this->makeFigmaDirectory();
    }


    public function handle(): int
    {
        Project::query()->chunk(100, function($projects) {
            foreach ($projects as $project) {
                $this->backup($project);
            }
        });


        return SymfonyCommand::SUCCESS;
    }

    private function backup(Project $project): void
    {
        $process = new Process([
            'figma-backup',
            '-e', $this->email,
            '-p', $this->password,
            '-t', $this->token,
            '--projects-ids', $project->figma_id
        ]);

        $process->setWorkingDirectory($this->workingDirectory);
        $process->setTimeout(self::TIMEOUT);
        $process->setIdleTimeout(self::TIMEOUT);
        $process->run();

        if ($process->isSuccessful()) {
            $files = $this->getFigFiles();

            foreach ($files as $file) {
                $name = $file->getBasename() . ' - ' . now()->toDateTimeString() . $file->getExtension();
                $path = "$project->slug/$name";

                $stored = $this->storeFigFile($path, $file);

                if ($stored) {
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
        }
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
        Storage::disk(self::BACKUP_DISK)->delete($path);
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
        $telegramIds = config('settings.telegram-to');

        Notification::send($telegramIds, new SendBackupNotification($backup));
    }
}
