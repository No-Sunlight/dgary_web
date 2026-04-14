<?

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function create(array $items): Order
    {
        return DB::transaction(function () use ($items) {

            $order = Order::create([
                'subtotal' => 0,
                'total' => 0,
                'status' => 'Completed',
                'type' => 'in_store',
                'discount' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {

                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stock insuficiente de {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                // Descontar stock
                $product->decrement('stock', $item['quantity']);

                // GENERAR MOVIMIENTO SOLO PARA CAJA
                if ($order->type === 'in_store') {
                    InventoryMovement::create([
                        'type' => 'sale',
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'direction' => 'out',
                        'reason' => 'Venta en caja (Order #' . $order->id . ')',
                    ]);
                }

                $total += $subtotal;
            }

            $order->update([
                'subtotal' => $total,
                'total' => $total,
            ]);

            return $order;
        });
    }
}