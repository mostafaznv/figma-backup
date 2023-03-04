<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CreateUserController extends Controller
{
    public function add(): View
    {
        return view('profile.add', [
            'users' => User::query()->paginate()
        ]);
    }

    public function create(CreateUserRequest $request): RedirectResponse
    {
        User::query()->create($request->validated());

        return Redirect::route('profile.index');
    }
}
