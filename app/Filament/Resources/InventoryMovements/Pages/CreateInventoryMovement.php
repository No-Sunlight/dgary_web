<?php

namespace App\Filament\Resources\InventoryMovements\Pages;

use App\Filament\Resources\InventoryMovements\InventoryMovementResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Product;
use App\Models\supply;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateInventoryMovement extends CreateRecord
{
    protected static string $resource = InventoryMovementResource::class;

    protected function afterCreate(): void
    {
        DB::transaction(function () {

            $data = $this->record;

            // PRODUCTO
            if ($data->product_id) {
                $stock = Product::find($data->product_id);

                if (!$stock) {
                    throw new Exception("No hay stock para este producto");
                }

                if ($data->direction === 'out') {
                    if ($stock->stock < $data->quantity) {
                        throw new Exception("Stock insuficiente");
                    }

                    $stock->decrement('stock', $data->quantity);
                } else {
                    $stock->increment('stock', $data->quantity);
                }
            }

            // INSUMO
            if ($data->supply_id) {
                $supply = Supply::find($data->supply_id);

                if ($data->direction === 'out') {
                    if ($supply->stock < $data->quantity) {
                        throw new Exception("Stock insuficiente");
                    }

                    $supply->decrement('stock', $data->quantity);
                } else {
                    $supply->increment('stock', $data->quantity);
                }
            }
        });
    }
}
