<?php

namespace App\Filament\Resources\RecurringTransfers;

use App\Filament\Concerns\BulkDeleter;
use App\Filament\Concerns\UserFilterable;
use App\Filament\Resources\RecurringTransfers\Pages\CreateRecurringTransfer;
use App\Filament\Resources\RecurringTransfers\Pages\EditRecurringTransfer;
use App\Filament\Resources\RecurringTransfers\Pages\ListRecurringTransfers;
use App\Filament\Resources\RecurringTransfers\Schemas\RecurringTransferForm;
use App\Filament\Resources\RecurringTransfers\Tables\RecurringTransfersTable;
use App\Models\RecurringTransfer;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RecurringTransferResource extends Resource
{
    use BulkDeleter, UserFilterable;

    protected static ?string $model = RecurringTransfer::class;

    protected static ?string $slug = 'recurring-transfers';

    protected static string|\UnitEnum|null $navigationGroup = 'Recurring Transactions';

    public static function form(Schema $schema): Schema
    {
        return RecurringTransferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecurringTransfersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecurringTransfers::route('/'),
            'create' => CreateRecurringTransfer::route('/create'),
            'edit' => EditRecurringTransfer::route('/{record}/edit'),
        ];
    }
}
