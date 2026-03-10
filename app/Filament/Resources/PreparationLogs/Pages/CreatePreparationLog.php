<?php

namespace App\Filament\Resources\PreparationLogs\Pages;

use App\Filament\Resources\PreparationLogs\PreparationLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePreparationLog extends CreateRecord
{
    protected static string $resource = PreparationLogResource::class;
}
