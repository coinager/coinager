<?php

namespace App\Filament\Resources\People\Pages;

use App\Filament\Exports\PersonExporter;
use App\Filament\Imports\PersonImporter;
use App\Filament\Resources\People\PersonResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListPeople extends ListRecords
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(PersonImporter::class),
            ExportAction::make()
                ->exporter(PersonExporter::class)
                ->formats([
                    ExportFormat::Csv,
                ]),
        ];
    }
}
