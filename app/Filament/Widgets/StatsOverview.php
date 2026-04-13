<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // 1. Filter Orders by the current month
        $ordersThisMonth = Order::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ])->count();

        // 2. Filter Deliveries where status is NOT completed
        // IMPORTANT: Change 'completed' to whatever exact string or integer you use in your database (e.g., 'completado', 'entregado', or 3)
        $activeDeliveries = Delivery::where('status', '!=', 'completed')->count();

        return [
            Stat::make('Ordenes (Este mes)', $ordersThisMonth),
            Stat::make('Pedidos en curso', $activeDeliveries),
        ];
    }
}