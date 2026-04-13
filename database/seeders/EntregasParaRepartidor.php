<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EntregasParaRepartidor extends Seeder
{
    /**
     * Crea entregas para el usuario repartidor1@gmail.com (ID 3)
     */
    public function run(): void
    {
        // Obtener el repartidor existente
        $repartidor = User::where('email', 'repartidor1@gmail.com')->first();
        
        if (!$repartidor) {
            $this->command->error('Repartidor repartidor1@gmail.com no existe');
            return;
        }

        $this->command->info("Asignando entregas al repartidor: {$repartidor->name} ({$repartidor->email})");

        // Obtener o crear categoría
        $category = Category::firstOrCreate(
            ['name' => 'Paletas'],
            ['description' => 'Paletas artesanales de hielo']
        );

        // Obtener o crear productos
        $mangoPop = Product::firstOrCreate(
            ['name' => 'Paleta de Mango'],
            [
                'category_id' => $category->id,
                'price' => 25.0,
                'stock' => 50,
                'points' => 5,
            ]
        );

        $strawberryPop = Product::firstOrCreate(
            ['name' => 'Paleta de Fresa'],
            [
                'category_id' => $category->id,
                'price' => 25.0,
                'stock' => 50,
                'points' => 5,
            ]
        );

        $chocolatePop = Product::firstOrCreate(
            ['name' => 'Paleta de Chocolate'],
            [
                'category_id' => $category->id,
                'price' => 30.0,
                'stock' => 50,
                'points' => 5,
            ]
        );

        // Crear 3 clientes de prueba
        $customers = [
            [
                'name' => 'Carlos García',
                'email' => 'carlos.garcia@example.com',
                'phone' => '3312222222',
                'address' => 'Calle Hidalgo 456, Centro',
                'lat' => 20.6638,
                'lng' => -103.3436,
            ],
            [
                'name' => 'María López',
                'email' => 'maria.lopez@example.com',
                'phone' => '3313333333',
                'address' => 'Avenida Chapultepec 789, Jalisco',
                'lat' => 20.6613,
                'lng' => -103.3510,
            ],
            [
                'name' => 'Rosa Martínez',
                'email' => 'rosa.martinez@example.com',
                'phone' => '3314444444',
                'address' => 'Paseo Montejo 321, Zona Rosa',
                'lat' => 20.6676,
                'lng' => -103.3333,
            ],
        ];

        $deliveryConfigs = [
            // Entrega 1: Pending
            [
                'customer' => $customers[0],
                'products' => [
                    ['product' => $mangoPop, 'qty' => 2],
                    ['product' => $strawberryPop, 'qty' => 1],
                ],
                'status' => 'pending',
                'total' => 75.0,
            ],
            // Entrega 2: Ready
            [
                'customer' => $customers[1],
                'products' => [
                    ['product' => $chocolatePop, 'qty' => 3],
                ],
                'status' => 'ready',
                'total' => 90.0,
            ],
            // Entrega 3: In Transit
            [
                'customer' => $customers[2],
                'products' => [
                    ['product' => $mangoPop, 'qty' => 1],
                    ['product' => $chocolatePop, 'qty' => 2],
                ],
                'status' => 'in_transit',
                'total' => 85.0,
            ],
        ];

        $createdCount = 0;

        foreach ($deliveryConfigs as $i => $config) {
            $customer = $config['customer'];
            
            // Crear o obtener cliente
            $cust = Customer::firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                    'password' => Hash::make('password123'),
                    'points' => 0,
                ]
            );

            // Crear orden
            $total = $config['total'];
            $order = Order::create([
                'customer_id' => $cust->id,
                'subtotal' => $total,
                'discount' => 0,
                'delivery_fee' => 0,
                'tax' => 0,
                'total' => $total,
                'status' => 'Pending',
                'type' => 'delivery',
                'notes' => 'Prueba desde seeder',
            ]);

            // Crear detalles
            foreach ($config['products'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['product']->price,
                    'subtotal' => $item['product']->price * $item['qty'],
                ]);
            }

            // Crear delivery
            Delivery::create([
                'order_id' => $order->id,
                'user_id' => $repartidor->id,
                'address' => $customer['address'],
                'total' => $total,
                'status' => $config['status'],
                'destination_lat' => $customer['lat'],
                'destination_lng' => $customer['lng'],
                'driver_lat' => null,
                'driver_lng' => null,
                'driver_location_updated_at' => null,
                'notes' => 'Entrega #' . ($i+1),
            ]);

            $createdCount++;
            
            $this->command->line("✅ Entrega creada: {$customer['name']} (Status: {$config['status']})");
        }

        $this->command->info('');
        $this->command->info("✨ Se crearon {$createdCount} entregas para {$repartidor->name}");
        $this->command->info('Credenciales: repartidor1@gmail.com / password123');
        $this->command->info('');
    }
}
