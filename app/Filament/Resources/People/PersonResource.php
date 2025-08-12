<?php

namespace App\Filament\Resources\People;

use App\Filament\Resources\People\Pages\CreatePerson;
use App\Filament\Resources\People\Pages\EditPerson;
use App\Filament\Resources\People\Pages\ListPeople;
use App\Filament\Resources\People\Schemas\PersonForm;
use App\Filament\Resources\People\Tables\PeopleTable;
use App\Models\Person;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $slug = 'people';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Schema $schema): Schema
    {
        return PersonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeopleTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}
