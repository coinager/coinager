<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('denies guest access', function () {
    Account::factory()->count(3)->create();

    $this->getJson('api/accounts')
        ->assertUnauthorized();
});

it('returns accounts', function () {
    $user = User::factory()->create();
    Account::factory()->count(3)->for($user)->create();

    $this->actingAs($user)->getJson('api/accounts')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});
