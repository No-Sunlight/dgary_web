<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\StockCount;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


    protected function afterCreate(): void {
           $record = $this->record;
           $stock_record = new StockCount();
           $stock_record->product_id=$record->id;
           $stock_record->save();

         }
}
