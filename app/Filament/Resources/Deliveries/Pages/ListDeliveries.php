<?php

namespace App\Filament\Resources\Deliveries\Pages;

use App\Filament\Resources\Coupons\Widgets\CouponsMetrics;
use App\Filament\Resources\Deliveries\DeliveryResource;
use App\Filament\Resources\Deliveries\Widgets\DeliveryMetrics;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveries extends ListRecords
{
    protected static string $resource = DeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [

         DeliveryMetrics::class,

        ];
    }
}
