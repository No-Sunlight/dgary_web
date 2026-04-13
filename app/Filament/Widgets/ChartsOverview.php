<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChartsOverview extends ChartWidget
{
    protected ?string $heading = 'Ventas';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        //Inicializar 
        $data = collect();
        $labels = collect();


        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M'); // 'Jan', 'Feb', 'Mar', etc.
            
            $labels->push($monthName);
            $data->put($monthName, 0); 
        }

        $orders = Order::whereBetween('created_at', [
            now()->subMonths(2)->startOfMonth(),
            now()->endOfMonth(),
        ])->get();

        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->created_at->format('M');
        });

        foreach ($groupedOrders as $month => $records) {
            if ($data->has($month)) {
                $data[$month] = $records->count(); 

            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por mes',
                    'data' => $data->values()->toArray(),
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}