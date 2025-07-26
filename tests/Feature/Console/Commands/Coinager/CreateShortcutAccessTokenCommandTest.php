<?php

use App\Console\Commands\Coinager\CreateShortcutAccessTokenCommand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates access token for user', function () {

    User::factory()->createMany([
        ['email' => 'u1@example.com'],
        ['email' => 'u2@example.com'],
    ]);

    $this->artisan(CreateShortcutAccessTokenCommand::class)
        ->expectsChoice('Select user', 'u2@example.com', [
            'u1@example.com',
            'u2@example.com',
        ])
        ->assertSuccessful();
});
