<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderBy('id')->paginate();

        return view('profile.index', [
            'users' => $users
        ]);
    }

    public function view(User $user): View
    {
        return view('profile.view', [
            'user' => $user,
        ]);
    }
}
