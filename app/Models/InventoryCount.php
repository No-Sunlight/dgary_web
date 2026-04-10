<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryCount extends Model
{
    protected $fillable = [
        'type',
        'supply_id',
        'product_id',
        'applied',
        'applied_at'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\InventoryCountItem::class);
    }

    public function apply(): void
    {
        if ($this->applied) {
            throw new \Exception('Este inventario ya fue aplicado.');
        }

        // 🔴 VALIDACIÓN AQUÍ
        $this->validateBeforeApply();

        DB::transaction(function () {

            foreach ($this->items as $item) {

                $difference = $item->stock_real - $item->stock_system;

                if ($difference == 0) {
                    continue;
                }

                \App\Models\InventoryMovement::create([
                    'type' => 'adjustment',
                    'product_id' => $item->product_id,
                    'supply_id' => $item->supply_id,
                    'quantity' => abs($difference),
                    'direction' => $difference > 0 ? 'in' : 'out',
                    'reason' => 'Ajuste por inventario',
                ]);

                if ($item->product_id) {
                    \App\Models\Product::find($item->product_id)
                        ->increment('stock', $difference);
                }

                if ($item->supply_id) {
                    \App\Models\Supply::find($item->supply_id)
                        ->increment('stock', $difference);
                }
            }

            $this->update([
                'applied' => true,
                'applied_at' => now(),
            ]);
        });
    }

    public function validateBeforeApply(): void
    {
        $items = $this->items;

        if ($items->isEmpty()) {
            throw new \Exception('Este inventario no tiene registros.');
        }

        // 1. Validar que todos tengan stock_real
        $missing = $items->whereNull('stock_real');

        if ($missing->count() > 0) {
            throw new \Exception("Faltan {$missing->count()} productos por contar.");
        }

        // 2. Validar valores negativos (opcional según tu lógica)
        $invalid = $items->filter(fn($item) => $item->stock_real < 0);

        if ($invalid->count() > 0) {
            throw new \Exception('Hay valores negativos inválidos.');
        }

        // 3. Validar si todo está en cero diferencia (opcional)
        $noChanges = $items->every(
            fn($item) =>
            ($item->stock_real - $item->stock_system) == 0
        );

        if ($noChanges) {
            throw new \Exception('No hay diferencias para aplicar.');
        }
    }

    public function getTotalLossAttribute(): float
    {
        return $this->items->sum(function ($item) {

            if ($item->stock_real === null)
                return 0;

            $diff = $item->stock_real - $item->stock_system;

            if ($diff >= 0)
                return 0;

            $cost = $item->product
                ? $item->product->price
                : $item->supply?->price ?? 0;

            return abs($diff) * $cost;
        });
    }

    public function getTotalGainAttribute(): float
    {
        return $this->items->sum(function ($item) {

            if ($item->stock_real === null)
                return 0;

            $diff = $item->stock_real - $item->stock_system;

            if ($diff <= 0)
                return 0;

            $cost = $item->product
                ? $item->product->price
                : $item->supply?->price ?? 0;

            return $diff * $cost;
        });
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_gain - $this->total_loss;
    }
}
