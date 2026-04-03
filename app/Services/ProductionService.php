<?

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Recipe;
use App\Models\Supply;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductionService
{
    public function produce(int $productId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $quantity) {

            $recipe = Recipe::where('product_id', $productId)->firstOrFail();

            $factor = $quantity / $recipe->produced_quantity;

            foreach ($recipe->supplies as $item) {

                $required = $item->quantity * $factor;

                $supply = $item->supply;

                if ($supply->stock < $required) {
                    throw new Exception("Stock insuficiente de {$supply->name}");
                }

                $supply->decrement('stock', $required);
            }

            // aumentar producto terminado
            $stock = ProductStock::firstOrCreate(
                ['product_id' => $productId],
                ['stock' => 0]
            );

            $stock->increment('stock', $quantity);
        });
    }
}