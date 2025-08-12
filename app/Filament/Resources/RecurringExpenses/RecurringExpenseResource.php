<?php

namespace App\Filament\Resources\RecurringExpenses;

use App\Filament\Resources\RecurringExpenses\Pages\CreateRecurringExpense;
use App\Filament\Resources\RecurringExpenses\Pages\EditRecurringExpense;
use App\Filament\Resources\RecurringExpenses\Pages\ListRecurringExpenses;
use App\Filament\Resources\RecurringExpenses\Schemas\RecurringExpenseForm;
use App\Filament\Resources\RecurringExpenses\Tables\RecurringExpensesTable;
use App\Models\RecurringExpense;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecurringExpenseResource extends Resource
{
    protected static ?string $model = RecurringExpense::class;

    protected static ?string $slug = 'recurring-expenses';

    protected static string|\UnitEnum|null $navigationGroup = 'Recurring Transactions';

    public static function form(Schema $schema): Schema
    {
        return RecurringExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecurringExpensesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecurringExpenses::route('/'),
            'create' => CreateRecurringExpense::route('/create'),
            'edit' => EditRecurringExpense::route('/{record}/edit'),
        ];
    }
}
