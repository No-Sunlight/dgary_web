<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }


    protected function mutateFormDataBeforeFill(array $data): array
{  



//     $couponinfo=CustomerCoupon::with('coupons')->where('id','=',$data['coupon_id'])->first();//->where('id','=',$data['coupon_id']);
//     dd($couponinfo->coupons->name);
//     $array = array(
//     $data['coupon_id'] => $couponinfo->coupons->name,
// );

//    $data['coupon_id'] = $array;
     return $data;
}

    
}
