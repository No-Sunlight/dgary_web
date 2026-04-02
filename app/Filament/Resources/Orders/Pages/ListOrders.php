<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Widgets\OrdersMetrics;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Icons\Heroicon;


class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }



            protected function getHeaderWidgets(): array
    {
        return [
         OrdersMetrics::class,
         

        ];
    }
}
