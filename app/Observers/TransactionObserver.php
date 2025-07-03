<?php

namespace App\Observers;

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
            $this->adjustAccountBalances(
                $transaction->getOriginal('account_id'),
                $transaction->getAttribute('account_id'),
                $transaction->getOriginal('amount'),
                $transaction->getAttribute('amount')
            );

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

    abstract protected function adjustAccountBalances(
        int $oldAccountKey,
        int $newAccountKey,
        float $oldAmount,
        float $newAmount
    ): void;
}
