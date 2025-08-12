<?php

namespace App\Filament\Resources\RecurringIncomes;

use App\Filament\Resources\RecurringIncomes\Pages\CreateRecurringIncome;
use App\Filament\Resources\RecurringIncomes\Pages\EditRecurringIncome;
use App\Filament\Resources\RecurringIncomes\Pages\ListRecurringIncomes;
use App\Filament\Resources\RecurringIncomes\Schemas\RecurringIncomeForm;
use App\Filament\Resources\RecurringIncomes\Tables\RecurringIncomesTable;
use App\Models\RecurringIncome;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecurringIncomeResource extends Resource
{
    protected static ?string $model = RecurringIncome::class;

    protected static ?string $slug = 'recurring-incomes';

    protected static string|\UnitEnum|null $navigationGroup = 'Recurring Transactions';

    public static function form(Schema $schema): Schema
    {
        return RecurringIncomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecurringIncomesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecurringIncomes::route('/'),
            'create' => CreateRecurringIncome::route('/create'),
            'edit' => EditRecurringIncome::route('/{record}/edit'),
        ];
    }
}
