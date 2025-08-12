<?php

namespace App\Filament\Resources\Balances\Pages;

use App\Filament\Resources\Balances\BalanceResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBalances extends ListRecords
{
    protected static string $resource = BalanceResource::class;

    public function filterTableQuery(Builder $query): Builder
    {
        return parent::filterTableQuery($query)
            ->whereIn('account_id', auth()->user()->accounts()->pluck('id'));
    }
}
