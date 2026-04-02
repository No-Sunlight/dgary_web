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





protected function mutateFormDataBeforeSave(array $data): array
{
  switch ($data['status']) {
    case "pending":
        $data['status'] = "ready";
        break;
    case "ready":
        $data ["in_transit"];
        break;
    // case 2:
    //     echo "i equals 2";
    //     break;
}

    
    $data['status'] = "in_transit";
    return $data;

}

}
