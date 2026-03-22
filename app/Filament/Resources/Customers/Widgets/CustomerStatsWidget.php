<?php

namespace App\Filament\Resources\Customers\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class CustomerStatsWidget extends StatsOverviewWidget
{
//protected ?string $heading = 'Most Bought Product';

    public ?Model $record = null; //Llamo al registro
    protected function getStats(): array
    {

    

        if (! $this->record) {
            return [];
            //Pienso que sería raro/improbable acceder a un record vacio, pero por si acaso
        }
          $id = $this->record->id;//Obtener la id

            //Obtener el producto mas comprado
            $product_stats = Order::query()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
             ->selectRaw('product_id, count(*) as total_bought')
             ->where('orders.customer_id',$id)
             ->groupBy('order_details.product_id')
            ->orderByDesc('total_bought')
            ->first();

            //Nota personal. Dentro de $product_stats viene la id del producto y la cantidad comprada
            if($product_stats==null) {
                $product_name="No ha realizado compras";
                return [Stat::make('El cliente no ha realizado compras', 0)
                ->descriptionIcon('heroicon-m-face-frown')
                ->color('primary'),];
            }//El cliente no ha hecho compras, no es necesario hacer mas queries 
            
            //Producto mas comprado
            $product_name= Product::find($product_stats->product_id)->name;
            
            //$purchases=Order::where('customer_id', $id)->withCount('details')->get();
        $count = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
        ->where("customer_id", $id)
        ->count();

        // Mes pasado
        $previousCount = Order::where('customer_id', $this->record->id)
        ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
        ->count();






        return[

                Stat::make('Producto mas comprado', $product_name)
                ->description("Unidades ". $product_stats->total_bought)
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),

                Stat::make('Total de compras', $count)
                ->description("Este mes")
                ->descriptionIcon($count>=$previousCount? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($count>=$previousCount? 'success' : 'danger')



                
        ];

    }
}
