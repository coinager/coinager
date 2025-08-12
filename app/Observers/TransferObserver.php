<?php

namespace App\Observers;

use App\Models\Transfer;

readonly class TransferObserver
{
    public function created(Transfer $transfer): void
    {
        $transfer->creditor()->increment('current_balance', $transfer->amount);
        $transfer->debtor()->decrement('current_balance', $transfer->amount);
    }

    public function updated(Transfer $transfer): void
    {
        if ($transfer->isClean(['amount'])) {
            return;
        }

        $diff = $this->getUpdatedAmountDiff($transfer);

        $transfer->creditor()->increment('current_balance', $diff);
        $transfer->debtor()->decrement('current_balance', $diff);
    }

    public function deleted(Transfer $transfer): void
    {
        $transfer->debtor()->increment('current_balance', $transfer->amount);
        $transfer->creditor()->decrement('current_balance', $transfer->amount);
    }

    private function getUpdatedAmountDiff(Transfer $transfer): float
    {
        $oldAmount = $transfer->getOriginal('amount');
        $newAmount = $transfer->getAttribute('amount');

        return $newAmount - $oldAmount;
    }
}
