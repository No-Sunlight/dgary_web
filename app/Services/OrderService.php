<?

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStock;
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
                $stock = ProductStock::where('product_id', $product->id)->first();

                if (!$stock || $stock->stock < $item['quantity']) {
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

                $stock->decrement('stock', $item['quantity']);

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