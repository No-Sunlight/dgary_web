<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class ProductionService
{
    public static function produce(int $recipeId, float $quantity): void
    {
        DB::transaction(function () use ($recipeId, $quantity) {

            $recipe = Recipe::with('details.supply', 'product')->findOrFail($recipeId);

            $product = $recipe->product;

            $factor = $quantity / $recipe->produced_quantity;

            // DESCONTAR INSUMOS
            foreach ($recipe->details as $detail) {

                $supply = $detail->supply;
                $consume = $detail->amount * $factor;

                if ($supply->stock < $consume) {
                    throw new \Exception("Stock insuficiente para {$supply->name}");
                }

                $supply->decrement('stock', $consume);

                InventoryMovement::create([
                    'type' => 'produccion',
                    'supply_id' => $supply->id,
                    'quantity' => $consume,
                    'direction' => 'out',
                    'reason' => 'Consumo por producción',
                ]);
            }

            // AUMENTAR PRODUCTO
            $product->increment('stock', $quantity);

            InventoryMovement::create([
                'type' => 'produccion',
                'product_id' => $product->id,
                'quantity' => $quantity,
                'direction' => 'in',
                'reason' => 'Producción realizada',
            ]);
        });
    }
}