<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Label;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {

        //Esta parte se va a quedar simplemente para mostrar detalles. Para la accion view
        //Pienso sobreescribir el formDelivery. 
        return $schema

            ->components([
                Select::make('user_id')
                    ->required()
                    ->label("Repartidor")
                    ->options(Driver::all()->pluck('name', 'id')),

                TextEntry::make('client')
                    ->label("Cliente")
                    ->state(function (Model $record, $set)//Nota personal. La variable siempre se tiene que definir commo $record para acceder al registro
                    {
                        $order = Order::with('details')->find($record->order_id);
                        $customer = Customer::find($order->customer_id);
                        $html = '<ul>';
                        foreach ($order->details as $detail) {
                            $product = Product::find($detail->product_id);
                            $html .= "<li>Nombre: {$product->name} Cantidad: {$detail->quantity}</li>";
                        }
                        $html .= '</ul>';

                        $set('phone', $customer->phone);
                        $set('details', $html);

                        return $customer->name;

                    }),

                TextEntry::make('phone')
                    ->label("Telefono"),



                TextInput::make('address')
                    ->label("Dirección")
                    ->disabled()
                    ->required(),
                Select::make('status')
                    ->label("Estatus")
                    ->options([
                        'pending' => 'Pending',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'in_transit' => 'In Transit'
                    ])
                    ->disabled()
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->disabled()
                    ->prefix("$")
                    ->numeric(),

                TextEntry::make('details')
                    ->label('Productos: ')
                    ->html(),

            ]);
    }
}
