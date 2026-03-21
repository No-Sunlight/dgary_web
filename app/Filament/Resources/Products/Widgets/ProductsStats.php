<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            
            Stat::make('Productos Totales', Product::count())
                ->description('Activos en catalogo')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

                Stat::make('Sin Stock', Product::where('stock', 0)->count())
                ->description('No disponibles')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

        ];
    }
}
