<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->state(fn (?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->state(fn (?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }
}
