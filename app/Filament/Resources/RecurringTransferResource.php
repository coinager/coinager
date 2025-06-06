<?php

namespace App\Filament\Resources;

use App\Enums\Frequency;
use App\Filament\Concerns\BulkDeleter;
use App\Filament\Concerns\UserFilterable;
use App\Filament\Resources\RecurringTransferResource\Pages;
use App\Models\Account;
use App\Models\RecurringTransfer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RecurringTransferResource extends Resource
{
    use BulkDeleter, UserFilterable;

    protected static ?string $model = RecurringTransfer::class;

    protected static ?string $slug = 'recurring-transfers';

    protected static ?string $navigationGroup = 'Recurring Transactions';

    public static function form(Form $form): Form
    {
        $accounts = Account::query()
            ->pluck('name', 'id');

        return $form
            ->schema([
                Select::make('creditor_id')
                    ->label('Creditor account')
                    ->helperText('The account where money is going to')
                    ->options($accounts)
                    ->searchable()
                    ->different('debtor_id')
                    ->required(),

                Select::make('debtor_id')
                    ->label('Debtor account')
                    ->helperText('The account where money is coming from')
                    ->options($accounts)
                    ->searchable()
                    ->different('creditor_id')
                    ->required(),

                TextInput::make('description')
                    ->required(),

                TextInput::make('amount')
                    ->numeric()
                    ->required(),

                DatePicker::make('next_transaction_at')
                    ->label('Next Transaction Date')
                    ->required()
                    ->after(today()),

                Select::make('frequency')
                    ->options(Frequency::class)
                    ->required(),

                TextInput::make('remaining_recurrences')
                    ->integer()
                    ->helperText('Leave blank for infinite recurrences'),

                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?RecurringTransfer $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?RecurringTransfer $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::getUserColumn(),

                TextColumn::make('creditor.name'),

                TextColumn::make('debtor.name'),

                TextColumn::make('description')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('next_transaction_at')
                    ->label('Next Transaction Date')
                    ->date(),

                TextColumn::make('frequency'),

                TextColumn::make('remaining_recurrences'),
            ])
            ->filters([
                self::getUserFilter(),

                SelectFilter::make('frequency')
                    ->options(Frequency::class),
            ])
            ->defaultSort('next_transaction_at')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    self::deleteBulkAction(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecurringTransfers::route('/'),
            'create' => Pages\CreateRecurringTransfer::route('/create'),
            'edit' => Pages\EditRecurringTransfer::route('/{record}/edit'),
        ];
    }
}
