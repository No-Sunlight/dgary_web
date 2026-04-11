<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\InventoryCount;

class InventoryLossChart extends ChartWidget
{
    protected ?string $heading = 'Pérdidas por inventario';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $counts = InventoryCount::with('items.product', 'items.supply')
            ->latest()
            ->take(10)
            ->get()
            ->reverse(); // importante para orden cronológico

        return [
            'datasets' => [
                [
                    'label' => 'Pérdidas ($)',
                    'data' => $counts->map(fn($c) => (float) $c->total_loss)->values()->toArray(),
                ],
            ],
            'labels' => $counts->map(fn($c) => $c->created_at->format('d/m'))->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
