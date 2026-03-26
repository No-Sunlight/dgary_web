<?php

namespace App\Filament\Resources\Deliveries\Pages;

use App\Filament\Resources\Deliveries\DeliveryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDelivery extends EditRecord
{
    protected static string $resource = DeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }



//     protected function mutateFormDataBeforeFill(array $data): array
// {
//     $data['status'] = auth()->id();

//     return $data;
// }

protected function mutateFormDataBeforeSave(array $data): array
{
    
    $data['status'] = "in_transit";
    return $data;

}

}
