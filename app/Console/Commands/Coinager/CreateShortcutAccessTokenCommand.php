<?php

namespace App\Console\Commands\Coinager;

use App\Models\User;
use Illuminate\Console\Command;

class CreateShortcutAccessTokenCommand extends Command
{
    protected $signature = 'coinager:create-shortcut-access-token';

    protected $description = 'Create a access token to access the Coinager API via Apple shortcuts';

    public function handle(): void
    {
        $users = User::query()->get()->keyBy('email');

        $selectedUserEmail = $this->choice('Select user', $users->pluck('email', 'id')->toArray());

        $this->info("Creating access token for {$selectedUserEmail}");

        /** @var User $user */
        $user = $users->get($selectedUserEmail);

        $token = $user->createToken('shortcuts');

        $this->line($token->plainTextToken);
    }
}
