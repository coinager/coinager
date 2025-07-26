<?php

use App\Models\Account;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('denies guest access', function () {
    $this->postJson(route('expenses.store'))
        ->assertUnauthorized();
});

it('stores expense', function () {
    $user = User::factory()->create();
    $account = Account::factory()->for($user)->create();

    $this
        ->actingAs($user)
        ->postJson(route('expenses.store'), [
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'expense 1',
            'transacted_at' => now()->subDay(),
        ])
        ->assertCreated();

    $this->assertDatabaseHas(Expense::class, [
        'account_id' => $account->id,
        'amount' => 100,
        'description' => 'expense 1',
        'transacted_at' => now()->subDay(),
    ]);
});

it('stores expense when transacted_at not set', function () {
    $user = User::factory()->create();
    $account = Account::factory()->for($user)->create();

    $this
        ->actingAs($user)
        ->postJson(route('expenses.store'), [
            'account_id' => $account->id,
            'amount' => 100,
            'description' => 'expense 1',
        ])
        ->assertCreated();

    $this->assertDatabaseHas(Expense::class, [
        'account_id' => $account->id,
        'amount' => 100,
        'description' => 'expense 1',
        'transacted_at' => now(),
    ]);
});
