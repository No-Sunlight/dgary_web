<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class OrdersMetrics extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        //Metricas basadas en el inicio y final del mes
        $startDate =Carbon::now()->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();


        $currentmonth = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousmonth = Order::whereBetween('created_at', [$startDate->subMonth()->startOfMonth(), $endDate->subMonth()->endOfMonth()])
        ->count();

        //Producto mas vendido
            $product_stats = Order::query()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
             ->selectRaw('product_id, count(*) as total_bought')
             ->groupBy('order_details.product_id')
             ->orderByDesc('total_bought')
             ->first();
        //Calcular el revenue del mes
        $revenue = Order::whereBetween('created_at',[$startDate,$endDate])->sum('total');
             
        //Obtner el nombre del producto
        $product = Product::find($product_stats->product_id);

        //Performance metric
        $currentmonth>=$previousmonth? $performance=True : $performance=False;

        //Cuanto aumento o disminuyo en porcentaje 
        $porcentage=($currentmonth-$previousmonth)/100*$previousmonth;



        return [
             Stat::make('Total de compras del mes', $currentmonth)
                ->description("Este mes ".$porcentage."%")
                ->descriptionIcon($performance? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($performance? 'success' : 'danger'),

                Stat::make('Producto mas comprado', $product->name)
                ->description("Unidades ". $product_stats->total_bought)
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),

                Stat::make('Ganancias del mes',$revenue."$")
                 ->description("Unidades ". $product_stats->total_bought)
                ->descriptionIcon(Heroicon::ArrowTrendingUp)
                ->color('primary'),
        ];



    }
}
