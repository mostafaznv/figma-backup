<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $users = User::query()->count();
        $projects = Project::query()->count();

        return view('dashboard', [
            'users'    => $users,
            'projects' => $projects
        ]);
    }
}
