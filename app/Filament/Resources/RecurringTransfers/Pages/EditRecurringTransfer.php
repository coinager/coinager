<?php

namespace App\Filament\Resources\RecurringTransfers\Pages;

use App\Filament\Resources\RecurringTransfers\RecurringTransferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecurringTransfer extends EditRecord
{
    protected static string $resource = RecurringTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
