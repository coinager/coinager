<?php

use App\Models\Account;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('denies guest access', function () {
    $this->postJson(route('incomes.store'))
        ->assertUnauthorized();
});

it('stores income', function () {
    $user = User::factory()->create();
    $account = Account::factory()->for($user)->create();

    $this
        ->actingAs($user)
        ->postJson(route('incomes.store'), [
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'income 1',
            'transacted_at' => now()->subDay(),
        ])
        ->assertCreated();

    $this->assertDatabaseHas(Income::class, [
        'account_id' => $account->id,
        'amount' => 100,
        'description' => 'income 1',
        'transacted_at' => now()->subDay(),
    ]);
});

it('stores income when transacted_at not set', function () {
    $user = User::factory()->create();
    $account = Account::factory()->for($user)->create();

    $this
        ->actingAs($user)
        ->postJson(route('incomes.store'), [
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'income 1',
        ])
        ->assertCreated();

    $this->assertDatabaseHas(Income::class, [
        'account_id' => $account->id,
        'amount' => 100,
        'description' => 'income 1',
        'transacted_at' => now(),
    ]);
});
