<?php

use App\Filament\Resources\ExpenseResource;
use App\Models\Account;
use App\Models\Balance;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use function Pest\Laravel\travelTo;
use function Pest\Livewire\livewire;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('subtracts account current_balance when created', function () {

    $newData = Expense::factory()->make([
        'amount' => 3000,
    ]);

    $account = Account::factory()->create([
        'current_balance' => 1000,
    ]);
    $person = Person::factory()->create();
    $category = Category::factory()->create();

    livewire(ExpenseResource\Pages\CreateExpense::class)
        ->fillForm([
            'description' => $newData->description,
            'person_id' => $person->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
            'amount' => $newData->amount,
            'transacted_at' => $newData->transacted_at,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Account::class, [
        'id' => $account->id,
        'current_balance' => -2000,
    ]);
});

it('adjusts account current_balance when updated', function (int $expenseAmount, int $accountBalance) {

    $account = Account::factory()->create([
        'current_balance' => 10_000,
    ]);

    $expense = Expense::factory()
        ->for($this->user)
        ->for($account)
        ->createQuietly([
            'amount' => 4000,
        ]);

    livewire(ExpenseResource\Pages\EditExpense::class, [
        'record' => $expense->getKey(),
    ])
        ->fillForm([
            'description' => $expense->description,
            'person_id' => $expense->person_id,
            'account_id' => $expense->account_id,
            'category_id' => $expense->category_id,
            'transacted_at' => $expense->transacted_at,

            'amount' => $expenseAmount,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Account::class, [
        'id' => $account->id,
        'current_balance' => $accountBalance,
    ]);
})->with([
    'decrease' => [6000, 8000],
    'increase' => [2000, 12000],
]);

it('adds account current_balance when removed', function () {
    $account = Account::factory()->create([
        'current_balance' => 1000,
    ]);
    $expense = Expense::factory()
        ->for($this->user)
        ->for($account)
        ->createQuietly([
            'amount' => 3000,
        ]);

    livewire(ExpenseResource\Pages\EditExpense::class, [
        'record' => $expense->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseHas(Account::class, [
        'id' => $account->id,
        'current_balance' => 4000,
    ]);
});

it('verifies account initial date adjustment when expense before today added',
    function (Carbon $transactedAt, Carbon $expectedAccountInitialDate) {
        travelTo(Carbon::create(2025, 01, 20));

        $account = Account::factory()->create([
            'initial_date' => Carbon::create(2025, 01, 10),
            'current_balance' => 5000,
        ]);
        Expense::factory()
            ->for($this->user)
            ->for($account)
            ->create([
                'transacted_at' => $transactedAt,
                'amount' => 4000,
            ]);

        $this->assertDatabaseHas(Account::class, [
            'id' => $account->id,
            'current_balance' => 1000,
            'initial_date' => $expectedAccountInitialDate,
        ]);

        $this->assertDatabaseHas(Balance::class, [
            'account_id' => $account->id,
            'is_initial_record' => true,
            'recorded_until' => $expectedAccountInitialDate,
        ]);
    })->with('predated entries');

it('verifies account initial date adjustment when expense before today updated',
    function (Carbon $transactedAt, Carbon $expectedAccountInitialDate) {
        travelTo(Carbon::create(2025, 01, 20));
        $account = Account::factory()->create([
            'initial_date' => $initialDate = Carbon::create(2025, 01, 10),
            'current_balance' => 5000,
        ]);

        $expense = Expense::factory()
            ->for($this->user)
            ->for($account)
            ->createQuietly([
                'transacted_at' => $initialDate,
                'amount' => 4000,
            ])->refresh();

        $expense->update([
            'amount' => 3000, // reduced expense by 1000
            'transacted_at' => $transactedAt,
        ]);

        $this->assertDatabaseHas(Account::class, [
            'id' => $account->id,
            'current_balance' => 6000, // account adjusted by +1000
            'initial_date' => $expectedAccountInitialDate,
        ]);

        $this->assertDatabaseHas(Balance::class, [
            'account_id' => $account->id,
            'is_initial_record' => true,
            'recorded_until' => $expectedAccountInitialDate,
        ]);
    })->with('predated entries');

dataset('predated entries', [
    'date before account initial date' => [
        Carbon::create(2025, 01, 5),
        Carbon::create(2025, 01, 5),
    ],
    'date after account initial date' => [
        Carbon::create(2025, 01, 15),
        Carbon::create(2025, 01, 10),
    ],
]);
