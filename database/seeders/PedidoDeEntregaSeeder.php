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

class PedidoDeEntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear o obtener una categoría
        $category = Category::firstOrCreate(
            ['name' => 'Paletas'],
            ['description' => 'Paletas artesanales de hielo']
        );

        // 2. Crear o obtener prdouctos (Paletas)
        $palletaMango = Product::firstOrCreate(
            ['name' => 'Paleta de mango'],
            [
                'category_id' => $category->id,
                'price' => 25.0,
                'stock' => 10,
                'points' => 5,
            ]
        );

        $paletaFresa = Product::firstOrCreate(
            ['name' => 'Paleta de fresa'],
            [
                'category_id' => $category->id,
                'price' => 25.0,
                'stock' => 10,
                'points' => 5,
            ]
        );

        // 3. Crear o obtener un cliente (Juan Pérez)
        $customer = Customer::firstOrCreate(
            ['email' => 'juan.perez@example.com'],
            [
                'name' => 'Juan Pérez',
                'phone' => '3311111111',
                'address' => 'Av. Vallarta 123, Guadalajara, Jalisco',
                'password' => Hash::make('password123'),
                'points' => 0,
            ]
        );

        // 4. Crear una orden
        $order = Order::create([
            'customer_id' => $customer->id,
            'subtotal' => 50.0,
            'discount' => 0,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => 50.0,
            'status' => 'Pending',
            'type' => 'delivery',
            'notes' => '• Paleta de mango\n• Paleta de fresa\n\nCoordenadas: 20.6597, -103.3496',
        ]);

        // 5. Crear detalles de la orden
        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $palletaMango->id,
            'quantity' => 1,
            'unit_price' => 25.0,
            'subtotal' => 25.0,
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $paletaFresa->id,
            'quantity' => 1,
            'unit_price' => 25.0,
            'subtotal' => 25.0,
        ]);

        // 6. Obtener o crear un conductor
        $driver = User::firstOrCreate(
            ['email' => 'driver@example.com'],
            [
                'name' => 'Conductor de Prueba',
                'password' => Hash::make('password123'),
                'vehicle' => 'Motocicleta',
                'license_plate' => 'ABC-1234',
            ]
        );

        // 7. Crear la entrega (delivery)
        Delivery::create([
            'order_id' => $order->id,
            'user_id' => $driver->id, // Asignar al conductor
            'address' => 'Av. Vallarta 123, Guadalajara, Jalisco',
            'total' => $order->total,
            'status' => 'pending',
            'destination_lat' => 20.6597,
            'destination_lng' => -103.3496,
            'driver_lat' => null,
            'driver_lng' => null,
            'driver_location_updated_at' => null,
        ]);

        $this->command->info('');
        $this->command->info('✅ Pedido de entrega creado exitosamente!');
        $this->command->info('');
        $this->command->line("Cliente      : {$customer->name}");
        $this->command->line("Teléfono     : {$customer->phone}");
        $this->command->line("Dirección    : {$customer->address}");
        $this->command->line("");
        $this->command->line("Orden ID     : {$order->id}");
        $this->command->line("Total        : \${$order->total}");
        $this->command->line("Estado       : {$order->status}");
        $this->command->line("");
        $this->command->line("Conductor    : {$driver->name}");
        $this->command->line("Vehículo     : {$driver->vehicle}");
        $this->command->line("Placa        : {$driver->license_plate}");
        $this->command->line("");
        $this->command->line("Coordenadas  : 20.6597, -103.3496");
        $this->command->line("Productos    : Paleta de mango, Paleta de fresa");
        $this->command->info('');
    }
}
