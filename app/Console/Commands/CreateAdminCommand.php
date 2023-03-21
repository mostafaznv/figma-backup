<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CreateAdminCommand extends Command
{
    protected $signature   = 'make:admin';
    protected $description = 'Create a new admin';

    public function handle(): int
    {
        $name = $this->ask('Please enter the name');
        $email = $this->ask('Please enter the email');
        $password = $this->secret('Please enter the password');

        $user = User::query()->firstOrNew([
            'email' => $email
        ]);

        if ($user->exists()) {
            $answer = $this->ask('Email exists! Do you want to continue? (y/n)');

            if (!in_array($answer, ['y', 'Y', 'yes', 'Yes'])) {
                return SymfonyCommand::FAILURE;
            }
        }


        $user->name = $name;
        $user->password = Hash::make($password);
        $user->save();

        $this->info($user->wasRecentlyCreated ? "✅  Created successfully" : "✅  Updated successfully");

        return SymfonyCommand::SUCCESS;
    }
}
