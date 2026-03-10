<?php

namespace App\Filament\Resources\PreparationLogs\Pages;

use App\Filament\Resources\PreparationLogs\PreparationLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPreparationLogs extends ListRecords
{
    protected static string $resource = PreparationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
