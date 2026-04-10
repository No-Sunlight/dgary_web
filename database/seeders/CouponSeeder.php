<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'name' => '$25 de Descuento',
                'code' => 'DESCUENTO25',
                'description' => 'Descuento de $25 en tu próxima compra',
                'discount_percent' => 0, // No se usa porcentaje, es monto fijo
                'points_price' => 100,
                'is_active' => true,
                'minimum_purchase' => 100.00,
                'uses_count' => null, // Sin límite de usos
                'used_count' => 0,
            ],
            [
                'name' => '$15 de Descuento',
                'code' => 'DESCUENTO15',
                'description' => 'Obtén $15 de descuento en tu pedido',
                'discount_percent' => 0,
                'points_price' => 60,
                'is_active' => true,
                'minimum_purchase' => 50.00,
                'uses_count' => null,
                'used_count' => 0,
            ],
            [
                'name' => '$50 de Descuento',
                'code' => 'DESCUENTO50',
                'description' => 'Gran descuento de $50 en compras mayores a $200',
                'discount_percent' => 0,
                'points_price' => 200,
                'is_active' => true,
                'minimum_purchase' => 200.00,
                'uses_count' => null,
                'used_count' => 0,
            ],
            [
                'name' => '10% de Descuento',
                'code' => 'DESC10PORCIENTO',
                'description' => 'Descuento del 10% en toda tu compra',
                'discount_percent' => 10,
                'points_price' => 80,
                'is_active' => true,
                'minimum_purchase' => null,
                'uses_count' => null,
                'used_count' => 0,
            ],
            [
                'name' => 'Envío Gratis',
                'code' => 'ENVIOGRATIS',
                'description' => 'Obtén envío gratis en tu próxima orden',
                'discount_percent' => 0,
                'points_price' => 120,
                'is_active' => true,
                'minimum_purchase' => 150.00,
                'uses_count' => null,
                'used_count' => 0,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
