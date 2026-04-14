<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class OrderValidationService
{
    public function validateItems(array $items): void
    {
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => ['El carrito está vacío'],
            ]);
        }

        $productIds = collect($items)->pluck('product_id')->unique();
        $existingProducts = Product::whereIn('id', $productIds)->pluck('id');

        if ($existingProducts->count() !== $productIds->count()) {
            throw ValidationException::withMessages([
                'items' => ['Uno o más productos no existen'],
            ]);
        }

        // Validar stock si es necesario (opcional)
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['quantity']) {
                throw ValidationException::withMessages([
                    "items.{$item['product_id']}" => [
                        "Stock insuficiente para {$product->name}. Disponible: {$product->stock}",
                    ],
                ]);
            }
        }
    }

    public function validateCoupon(?string $couponCode, float $subtotal): ?Coupon
    {
        if (!$couponCode) {
            return null;
        }

        $coupon = Coupon::query()
            ->active()
            ->when(
                ctype_digit($couponCode),
                fn ($query) => $query->where('id', (int) $couponCode),
                fn ($query) => $query->where('code', $couponCode)
            )
            ->first();

        if (!$coupon) {
            throw ValidationException::withMessages([
                'coupon_id' => ['Cupón no válido o expirado'],
            ]);
        }

        if ($coupon->minimum_purchase && $subtotal < $coupon->minimum_purchase) {
            throw ValidationException::withMessages([
                'coupon_id' => [
                    "Compra mínima de {$coupon->minimum_purchase} para aplicar este cupón",
                ],
            ]);
        }

        if ($coupon->uses_count && $coupon->used_count >= $coupon->uses_count) {
            throw ValidationException::withMessages([
                'coupon_id' => ['Cupón agotado'],
            ]);
        }

        return $coupon;
    }

    public function validate(
        array $items,
        ?string $couponCode = null
    ): void {
        $this->validateItems($items);
        
        if ($couponCode) {
            // Si se envía un cupón, calcular el subtotal para validar mínimo compra
            $subtotal = 0;
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $product->price * $item['quantity'];
            }
            $this->validateCoupon($couponCode, $subtotal);
        }
    }
}
