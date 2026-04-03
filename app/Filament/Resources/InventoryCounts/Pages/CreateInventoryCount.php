<?php

namespace App\Filament\Resources\InventoryCounts\Pages;

use App\Filament\Resources\InventoryCounts\InventoryCountResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ProductStock;
use App\Models\Supply;

class CreateInventoryCount extends CreateRecord
{
    protected static string $resource = InventoryCountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['type'] === 'product') {
            $stock = ProductStock::where('product_id', $data['product_id'])->first();

            $data['stock_system'] = $stock?->stock ?? 0;
        }

        if ($data['type'] === 'inventory') {
            $supply = Supply::find($data['supply_id']);

            $data['stock_system'] = $supply?->stock ?? 0;
        }

        $data['difference'] = $data['stock_real'] - $data['stock_system'];

        return $data;
    }
}
