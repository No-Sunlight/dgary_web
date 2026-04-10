<?php

namespace App\Filament\Resources\Productions\Pages;

use App\Filament\Resources\Productions\ProductionResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ProductionService;
use Illuminate\Support\Facades\DB;

class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            app(ProductionService::class)->produce(
                $this->record->product_id,
                $this->record->quantity
            );
        });
    }
}
