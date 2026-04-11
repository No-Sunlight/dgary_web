<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\InventoryCount;

class InventoryStats extends BaseWidget
{
        protected static ?int $sort = 4;
        protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        
        $count = InventoryCount::with('items.product', 'items.supply')
            ->latest()
            ->first();

        $totalLoss = $count?->total_loss ?? 0;
        $totalGain = $count?->total_gain ?? 0;
        $balance = $totalGain - $totalLoss;

        return [

            Stat::make('Pérdidas', '$' . number_format($totalLoss, 2))
                ->description('Último inventario')
                ->color('danger'),

            Stat::make('Ganancias', '$' . number_format($totalGain, 2))
                ->description('Último inventario')
                ->color('success'),

            Stat::make('Balance', '$' . number_format($balance, 2))
                ->description('Último inventario')  
                ->color($balance < 0 ? 'danger' : 'success'),

        ];
    }
}
