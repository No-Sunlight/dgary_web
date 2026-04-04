<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
        protected static ?int $sort = -1;
        protected int | string | array $columnSpan = 1;


    protected function getStats(): array
    {
        return [
            Stat::make('Ordenes', Order::count()),
            Stat::make('Pedidos en curso', Delivery::where('status','pending')->count()),



        ];
    }
}
