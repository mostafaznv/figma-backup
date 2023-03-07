<?php

namespace App\Http\Controllers;

use App\Consts\Cache;
use App\Models\Project;
use App\Models\ProjectBackup;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $users = User::query()->count();
        $projects = Project::query()->count();
        $totalDownloads = ProjectBackup::cache()->get(Cache::TOTAL_DOWNLOADS);
        $totalBackups = ProjectBackup::cache()->get(Cache::TOTAL_BACKUPS);
        $totalAvailableBackups = ProjectBackup::cache()->get(Cache::TOTAL_AVAILABLE_BACKUPS);

        return view('dashboard', [
            'users'                 => $users,
            'projects'              => $projects,
            'totalBackups'          => $totalBackups ?? 0,
            'totalAvailableBackups' => $totalAvailableBackups ?? 0,
            'totalDownloads'        => $totalDownloads ?? 0,
        ]);
    }
}
