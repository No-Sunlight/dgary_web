<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\InventoryMovement;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void
    {
        $total = $this->record->details()->sum('subtotal');
        $this->record->update(['total' => $total]);

        foreach ($this->record->details as $detail) {
            $supply = $detail->supply;
            if ($supply) {
                // Actualizar stock
                $supply->stock += $detail->quantity;
                $supply->save();

                // Registrar movimiento de inventario
                InventoryMovement::create([
                    'type' => 'purchase',              // tipo de movimiento
                    'supply_id' => $supply->id,        // referencia al insumo
                    'product_id' => null,              // no aplica
                    'production_id' => null,           // no aplica
                    'quantity' => $detail->quantity,   // cantidad comprada
                    'direction' => 'in',               // entrada al inventario
                    'reason' => 'Compra #' . $this->record->id, // opcional, referencia a la compra
                ]);
            }
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total'] = collect($data['details'] ?? [])
            ->pluck('subtotal')
            ->sum();

        return $data;
    }
}
