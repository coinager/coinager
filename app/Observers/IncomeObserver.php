<?php

namespace App\Observers;

use App\Models\Account;

class IncomeObserver extends TransactionObserver
{
    protected function getCurrentBalanceWhenCreated(float $existingBalance, float $difference): float
    {
        return $existingBalance + $difference;
    }

    protected function getCurrentBalanceWhenDeleted(float $existingBalance, float $difference): float
    {
        return $existingBalance - $difference;
    }

    protected function adjustAccountBalances(int $oldAccountKey, int $newAccountKey, float $oldAmount, float $newAmount): void
    {
        Account::whereKey($oldAccountKey)
            ->decrement('current_balance', $oldAmount);

        Account::whereKey($newAccountKey)
            ->increment('current_balance', $newAmount);
    }
}
