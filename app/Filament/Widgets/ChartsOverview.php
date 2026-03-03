<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ChartsOverview extends ChartWidget
{
    protected ?string $heading = 'Ventas';

    protected static ?int $sort = 2;


    protected function getData(): array
    {
      $data=  Order::all();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas por mes',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
