<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Deliveries extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        return [
        Stat::make('Pedidos en curso', Delivery::count()),

        ];
    }
}
