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

        return view('dashboard', [
            'users'          => $users,
            'projects'       => $projects,
            'totalDownloads' => $totalDownloads ?? 0
        ]);
    }
}
