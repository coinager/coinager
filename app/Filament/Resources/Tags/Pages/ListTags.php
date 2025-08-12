<?php

namespace App\Filament\Resources\Tags\Pages;

use App\Filament\Exports\TagExporter;
use App\Filament\Imports\TagImporter;
use App\Filament\Resources\Tags\TagResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(TagImporter::class),
            ExportAction::make()
                ->exporter(TagExporter::class)
                ->formats([
                    ExportFormat::Csv,
                ]),
        ];
    }
}
