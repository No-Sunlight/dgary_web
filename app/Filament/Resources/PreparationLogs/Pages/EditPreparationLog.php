<?php

namespace App\Filament\Resources\PreparationLogs\Pages;

use App\Filament\Resources\PreparationLogs\PreparationLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPreparationLog extends EditRecord
{
    protected static string $resource = PreparationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
