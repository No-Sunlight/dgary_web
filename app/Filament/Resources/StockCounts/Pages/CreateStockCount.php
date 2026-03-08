<?php

namespace App\Filament\Resources\StockCounts\Pages;

use App\Filament\Resources\StockCounts\StockCountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockCount extends CreateRecord
{
    protected static string $resource = StockCountResource::class;
}
