<?php

namespace App\Filament\Resources\PreparationLogs\Pages;

use App\Filament\Resources\PreparationLogs\PreparationLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPreparationLog extends ViewRecord
{
    protected static string $resource = PreparationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
