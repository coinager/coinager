<?php

namespace App\Filament\Resources\RecurringExpenses\Pages;

use App\Filament\Resources\RecurringExpenses\RecurringExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecurringExpense extends EditRecord
{
    protected static string $resource = RecurringExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
