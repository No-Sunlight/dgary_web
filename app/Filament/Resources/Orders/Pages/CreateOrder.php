<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\InventoryMovement;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!is_null($data["coupon_id"])) {
            $coupon = CustomerCoupon::find($data["coupon_id"]);
            if ($coupon) {
                $coupon->status = false;
                $coupon->save();
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $orderdetails = OrderDetail::where('order_id', '=', $record->id)->get();

        // CLIENTE REGISTRADO
        if (!is_null($record->customer_id)) {

            $points = 0;
            $customer = Customer::find($record->customer_id);

            foreach ($orderdetails as $details) {

                $product = Product::find($details->product_id);

                // Descontar stock
                $product->stock = $product->stock - $details->quantity;
                $product->save();

                // Generar movimiento de inventario
                InventoryMovement::create([
                    'type' => 'sale',
                    'product_id' => $product->id,
                    'quantity' => $details->quantity,
                    'direction' => 'out',
                    'reason' => 'Venta en caja (Order #' . $record->id . ')',
                ]);

                // Calcular puntos
                $points += ($product->points * $details->quantity);
            }

            $customer->points = $customer->points + $points;
            $customer->save();
        }

        // 🔹 CLIENTE NO REGISTRADO
        else {

            foreach ($orderdetails as $details) {

                $product = Product::find($details->product_id);

                // Descontar stock
                $product->stock = $product->stock - $details->quantity;
                $product->save();

                // Generar movimiento de inventario
                InventoryMovement::create([
                    'type' => 'sale',
                    'product_id' => $product->id,
                    'quantity' => $details->quantity,
                    'direction' => 'out',
                    'reason' => 'Venta en caja (Order #' . $record->id . ')',
                ]);
            }
        }
    }
}
