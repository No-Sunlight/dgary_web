<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;

class OrderCalculationService
{
    private const DELIVERY_THRESHOLD = 500;
    private const DELIVERY_FEE = 0;
    private const TAX_RATE = 0.00;

    public function calculateSubtotal(array $items): float
    {
        $subtotal = 0;
        
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal += $product->price * $item['quantity'];
        }
        
        return round($subtotal, 2);
    }

    public function applyDiscount(float $subtotal, ?string $couponCode): array
    {
        if (!$couponCode) {
            return [
                'amount' => 0,
                'percent' => 0,
                'coupon_info' => null,
            ];
        }

        $coupon = Coupon::where('code', $couponCode)->active()->first();
        
        if (!$coupon) {
            return [
                'amount' => 0,
                'percent' => 0,
                'coupon_info' => null,
            ];
        }

        $discountAmount = round(($subtotal * $coupon->discount_percent) / 100, 2);

        return [
            'amount' => $discountAmount,
            'percent' => $coupon->discount_percent,
            'coupon_info' => [
                'code' => $coupon->code,
                'description' => $coupon->description,
                'applied_discount' => $discountAmount,
            ],
        ];
    }

    public function calculateDelivery(float $subtotal): float
    {
        return $subtotal >= self::DELIVERY_THRESHOLD ? 0 : self::DELIVERY_FEE;
    }

    public function calculateTax(float $subtotal, float $discount): float
    {
        return round(($subtotal - $discount) * self::TAX_RATE, 2);
    }

    public function calculateTotal(
        float $subtotal,
        float $discount,
        float $deliveryFee,
        float $tax
    ): float {
        return round($subtotal - $discount + $deliveryFee + $tax, 2);
    }

    public function getOrderCalculation(
        array $items,
        ?string $couponCode = null
    ): array {
        $subtotal = $this->calculateSubtotal($items);
        $discountData = $this->applyDiscount($subtotal, $couponCode);
        $discount = $discountData['amount'];
        $deliveryFee = $this->calculateDelivery($subtotal);
        $tax = $this->calculateTax($subtotal, $discount);
        $total = $this->calculateTotal($subtotal, $discount, $deliveryFee, $tax);

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'discount_percent' => $discountData['percent'],
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'total' => $total,
            'coupon_info' => $discountData['coupon_info'],
            'items_count' => count($items),
        ];
    }
}
