<?php

use App\Filament\Resources\BalanceResource;
use App\Models\Account;
use App\Models\Balance;
use App\Models\User;
use App\Services\AccountTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

use function Pest\Laravel\mock;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render balance list page', function () {
    $this->get(BalanceResource::getUrl('index'))->assertSuccessful();
});

it('can display transactions action only for monthly and yearly record types', function () {
    $account = Account::factory()->for($this->user)->create();
    $initial = Balance::factory()->for($account)->initialRecord()->create();
    $monthly = Balance::factory()->for($account)->monthly()->create();
    $yearly = Balance::factory()->for($account)->yearly()->create();

    livewire(BalanceResource\Pages\ListBalances::class)
        ->assertTableActionVisible('transactions', $monthly)
        ->assertTableActionVisible('transactions', $yearly)
        ->assertTableActionHidden('transactions', $initial);
});

it('sends transactions over email', function () {
    $account = Account::factory()->for($this->user)->create();
    $balance = Balance::factory()->for($account)->monthly()->create();

    mock(AccountTransactionService::class, function (MockInterface $mock) {
        $mock->shouldReceive('sendTransactionsForBalancePeriod')->once();
    });

    livewire(BalanceResource\Pages\ListBalances::class)
        ->callTableAction('transactions', $balance);
});
