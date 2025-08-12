<?php

namespace App\Filament\Resources\Balances;

use App\Filament\Resources\Balances\Pages\ListBalances;
use App\Filament\Resources\Balances\Tables\BalancesTable;
use App\Models\Balance;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class BalanceResource extends Resource
{
    protected static ?string $model = Balance::class;

    protected static ?string $slug = 'balances';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    public static function table(Table $table): Table
    {
        return BalancesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBalances::route('/'),
        ];
    }
}
