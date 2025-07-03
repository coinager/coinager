<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Income;

abstract class TransactionObserver
{
    public function created(Income|Expense $transaction): void
    {
        $currentBalance = $this->getCurrentBalanceWhenCreated(
            $transaction->account->current_balance, $transaction->amount
        );

        $transaction->account()->update([
            'current_balance' => $currentBalance,
        ]);
    }

    public function updated(Income|Expense $transaction): void
    {
        if ($transaction->isClean(['amount', 'transacted_at', 'account_id'])) {
            return;
        }

        $diff = $this->getUpdatedAmountDiff($transaction);

        if ($transaction->wasChanged('account_id')) {
            $oldBalance = $transaction->getOriginal('amount');
            $newBalance = $transaction->getAttribute('amount');

            Account::whereKey($transaction->getOriginal('account_id'))
                ->decrement('current_balance', $oldBalance);

            Account::whereKey($transaction->getAttribute('account_id'))
                ->increment('current_balance', $newBalance);

            return;
        }

        $currentBalance = $this->getCurrentBalanceWhenCreated(
            $transaction->account->current_balance, $diff
        );

        $transaction->account->update([
            'current_balance' => $currentBalance,
        ]);
    }

    public function deleted(Income|Expense $transaction): void
    {
        $currentBalance = $this->getCurrentBalanceWhenDeleted(
            $transaction->account->current_balance, $transaction->amount
        );

        $transaction->account()->update([
            'current_balance' => $currentBalance,
        ]);
    }

    private function getUpdatedAmountDiff(Income|Expense $transaction): float
    {
        $originalAmount = $transaction->getOriginal('amount');
        $changedAmount = $transaction->getAttribute('amount');

        return $changedAmount - $originalAmount;
    }

    abstract protected function getCurrentBalanceWhenCreated(float $existingBalance, float $difference): float;

    abstract protected function getCurrentBalanceWhenDeleted(float $existingBalance, float $difference): float;
}
