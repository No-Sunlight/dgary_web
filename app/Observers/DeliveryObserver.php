<?php

namespace App\Observers;

use App\Models\Delivery;
use App\Models\InventoryMovement;
use App\Models\Product;

class DeliveryObserver
{
    public function updated(Delivery $delivery): void
    {
        if (
            $delivery->wasChanged('status') &&
            $delivery->status === 'in_transit'
        ) {

            $order = $delivery->order;

            if (!$order) {
                return;
            }

            foreach ($order->details as $detail) {

                $product = Product::find($detail->product_id);

                if (!$product) continue;

                // Descontar stock
                $product->stock -= $detail->quantity;
                $product->save();

                // Crear movimiento
                InventoryMovement::create([
                    'type' => 'sale',
                    'product_id' => $product->id,
                    'quantity' => $detail->quantity,
                    'direction' => 'out',
                    'reason' => 'Venta delivery (Delivery #' . $delivery->id . ')',
                ]);
            }
        }
    }
}