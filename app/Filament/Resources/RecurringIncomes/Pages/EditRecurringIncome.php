<?php

namespace App\Filament\Resources\RecurringIncomes\Pages;

use App\Filament\Resources\RecurringIncomes\RecurringIncomeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecurringIncome extends EditRecord
{
    protected static string $resource = RecurringIncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
