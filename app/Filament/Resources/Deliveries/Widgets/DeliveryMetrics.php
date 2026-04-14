<?php

namespace App\Filament\Resources\Deliveries\Widgets;

use App\Models\Delivery;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DeliveryMetrics extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pending_orders = Delivery::where('status','pending')->count();
        $unasigned_driver = Delivery::where('user_id',null)->count();
        $in_trasint = Delivery::where('status','in_transit')->count();

        return [
        stat::make('Pedidos Pendientes',$pending_orders)
            ->description("Pedidos sin preparar")
            ->descriptionIcon(Heroicon::Clock)
            ->color( 'alert'),

            Stat::make('Sin asignar repartidor',$unasigned_driver)
            ->description("No se han asignado repartidores")
            ->descriptionIcon(Heroicon::Signal)
            ->color("danger"),    

            Stat::make('Pedidos en transito',$in_trasint)
            ->description("En proceso de entrega")
            ->descriptionIcon(Heroicon::Truck)
            ->color('success')


        
        ];
    }
}
