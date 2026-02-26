<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\CustomerCoupon;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


     protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(!$data["discount"]==null){
            $coupon= CustomerCoupon::find($data["discount"]);
            $total=  $coupon->discount/100*$data["total"];
            $data["discount"]=$coupon->discount;
            $data["total"]=$total;
            $coupon->status=false;
        }


    return $data;
    }
}
