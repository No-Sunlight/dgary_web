<?php

namespace App\Filament\Resources\InventoryCounts\Pages;

use App\Filament\Resources\InventoryCounts\InventoryCountResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Product;
use App\Models\Supply;
use Illuminate\Support\Facades\DB;

class CreateInventoryCount extends CreateRecord
{
    protected static string $resource = InventoryCountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['applied'] = false;
        return $data;
    }

    protected function afterCreate(): void
    {
        $count = $this->record;

        // evitar duplicados
        if ($count->items()->exists()) {
            return;
        }

        if ($count->type === 'product') {

            $items = Product::all()->map(function ($product) use ($count) {
                return [
                    'inventory_count_id' => $count->id,
                    'product_id' => $product->id,
                    'stock_system' => $product->stock,
                    'stock_real' => null,
                    'difference' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
            DB::table('inventory_count_items')->insert($items);
        }

        if ($count->type === 'supply') {

            $items = Supply::all()->map(function ($supply) use ($count) {
                return [
                    'inventory_count_id' => $count->id,
                    'supply_id' => $supply->id,
                    'stock_system' => $supply->stock,
                    'stock_real' => null,
                    'difference' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
            DB::table('inventory_count_items')->insert($items);
        }
    }
}
