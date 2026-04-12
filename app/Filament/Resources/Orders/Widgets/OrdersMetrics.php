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
    // 1. Setup Dates (Using copy() to avoid mutating the original date)
    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();
    
    $prevStart = $startDate->copy()->subMonth()->startOfMonth();
    $prevEnd = $startDate->copy()->subMonth()->endOfMonth();

    // 2. Base Counts
    $currentmonth = Order::whereBetween('created_at', [$startDate, $endDate])->count();
    $previousmonth = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();

    // 3. Top Product (Safe Check)
    $product_stats = Order::query()
        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        ->selectRaw('product_id, count(*) as total_bought')
        ->groupBy('order_details.product_id')
        ->orderByDesc('total_bought')
        ->first();

    // Find the product only if stats exist
    $product = $product_stats ? Product::find($product_stats->product_id) : null;

    // 4. Safe Percentage Calculation
    // We check if previousmonth is 0 to avoid "Division by zero"
    $percentage = $previousmonth > 0 
        ? (($currentmonth - $previousmonth) / $previousmonth) * 100 
        : ($currentmonth > 0 ? 100 : 0);

    $performance = $currentmonth >= $previousmonth;
    $revenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');

    return [
        Stat::make('Total de compras del mes', $currentmonth)
            ->description("Este mes " . number_format($percentage, 2) . "%")
            ->descriptionIcon($performance ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($performance ? 'success' : 'danger'),

        Stat::make('Producto mas comprado', $product?->name ?? 'Sin datos')
            ->description("Unidades " . ($product_stats?->total_bought ?? 0))
            ->descriptionIcon('heroicon-m-star')
            ->color('primary'),

        Stat::make('Ganancias del mes', number_format($revenue, 2) . "$")
            ->description("Ventas registradas")
            ->descriptionIcon('heroicon-m-currency-dollar')
            ->color('success'),
    ];
}
}
