<?php

namespace App\Filament\Resources\RecurringIncomes\Pages;

use App\Filament\Resources\RecurringIncomes\RecurringIncomeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRecurringIncome extends CreateRecord
{
    protected static string $resource = RecurringIncomeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
