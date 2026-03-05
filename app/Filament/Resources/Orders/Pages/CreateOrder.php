<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Tiptap\Nodes\DetailsSummary;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;




    // protected function getCreateFormAction(): Action
    // {
    //     return parent::getCreateFormAction()
    //     ->submit(form:null)
    //     ->requiresConfirmation()
    //     ->modalDescription("Confimrar pedido")
    //     ->action(function ()
    //     {
    //         $this->closeActionModal();
    //         $this->cteate();
    //     });
    //     //

    // }

    


     protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(!$data["coupon_id"]==null){
            $coupon= CustomerCoupon::find($data["coupon_id"]);
            $coupon->status=false;
            $coupon->save();
        }

    return $data;
    }

         protected function afterCreate(): void
    {
            //Este es el metodo correcto para obtener el ultimo registro guardado
            $record = $this->record; //Debo de recordarlo para futuras referencias
            $orderdetails = OrderDetail::where('order_id','=',$record->id)->get();

            //Un cliente registrado ha hecho una orden en presencial, asi que es necesario añadir a sus puntos
            //Quise dividirlo para economizar el proceso de añadir puntos y que no se confundiera con el de un cliente no registrado
            if(!is_null($record->customer_id))
            {
                    $points=0;
                    $customer = Customer::find($record->customer_id);
                    foreach($orderdetails as $details){
                    $product = Product::find($details->product_id);
                    $product->stock = $product->stock - $details->quantity;
                    $points=$points+($product->points*$details->quantity);
                    $product->save();
                    }//For each
            $customer->points= $customer->points+$points;
             $customer->save();
             }

             

    //No lo hizo un cliente registrado
        else{
         foreach($orderdetails as $details){
            $product = Product::find($details->product_id);
            $product->stock = $product->stock - $details->quantity;
            $product->save();
             }
        }


    }


}
