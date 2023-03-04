<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class UpdateUserController extends Controller
{
    public function edit(User $user): View
    {
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(ProfileUpdateRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->validated());
        $user->save();

        return Redirect::back()->with('status', 'profile-updated');
    }

    public function destroy(DeleteUserRequest $request, User $user): RedirectResponse
    {
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('dashboard');
    }
}
