<?php

namespace App\Actions;

use stdClass;
use Exception;
use App\Consts\Disks;
use App\DTOs\BackupResultData;
use App\DTOs\ProcessWorkingDirData;
use App\Models\Project;
use App\Models\ProjectBackup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

final class BackupAction
{
    private readonly ProcessAction $process;


    public function __construct(
        private readonly SendTelegramMessageAction $telegram,
        private readonly FigmaStorageAction        $figmaStorage,
    ) {
        $this->process = new ProcessAction(
            ProcessWorkingDirData::make(Disks::FIGMA)
        );
    }


    public function run(Project $project): BackupResultData
    {
        $errorMessage = '';
        $this->figmaStorage->cleanup();

        try {
            $process = $this->cmd($project->figma_id);

            if ($process->isSuccessful()) {
                $files = $this->figmaStorage->files();

                foreach ($files as $file) {
                    $fileInfo = $this->path($project, $file);

                    $stored = $this->figmaStorage->store($fileInfo->path, $file);

                    if ($stored) {
                        $backup = $this->store($project, $fileInfo->name, $fileInfo->path, $file);

                        $this->telegram->notify($backup);
                    }
                }
            }
            else {
                $errorMessage = $process->getErrorOutput();
            }
        }
        catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
        finally {
            $this->figmaStorage->cleanup();
        }

        $this->telegram->warning(
            $project->name, $errorMessage, $project->hashtag
        );

        return BackupResultData::make($errorMessage);
    }

    private function cmd(int|string $projectId): Process
    {
        $service = config('services.figma');

        $cmd = [
            'figma-backup',
            '-e', $service['email'],
            '-p', $service['password'],
            '-t', $service['token'],
            '--projects-ids', $projectId
        ];

        return $this->process->run($cmd);
    }

    private function store(Project $project, string $fileName, string $path, UploadedFile $file): ProjectBackup
    {
        /**
         * @var ProjectBackup $backup
         */
        $backup = $project->backups()->create([
            'name' => Str::title($fileName),
            'path' => $path,
            'size' => $file->getSize()
        ]);

        return $backup;
    }

    private function path(Project $project, UploadedFile $file): stdClass
    {
        $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $pathName = $this->figmaStorage->prepareFileName($project->name, $fileName, $file->getExtension());

        $path = new stdClass();
        $path->name = $fileName;
        $path->path = "$project->slug/$pathName";

        return $path;
    }
}
