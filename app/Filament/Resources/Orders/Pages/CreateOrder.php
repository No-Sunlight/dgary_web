<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\OrderDetail;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Tiptap\Nodes\DetailsSummary;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


     protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(!$data["coupon_id"]==null){
            $coupon= CustomerCoupon::find($data["coupon_id"]);
           // $total=  $coupon->discount/100*$data["total"];
           //$data["discount"]=$coupon->discount;
            //$data["total"]=$total;
            $coupon->status=false;
            $coupon->save();
        }



    return $data;
    }

         protected function afterCreate(): void
    {
        //Este es el metodo correcto para obtener el ultimo registro guardado
        $record = $this->record; 
        $orderdetails = OrderDetail::where('order_id','=',$record->id)->get();

        if(!$record->coupon_id==null){
             $points=0;
            $customer = Customer::find($record->customer_id);
            foreach($orderdetails as $details){
            $product = Product::find($details->product_id);
            $product->stock = $product->stock - $details->quantity;
            $points=$points+$product->points*$details->quantity;
            $product->save();}
             $customer->save();
             }

        else{
         foreach($orderdetails as $details){
            $product = Product::find($details->product_id);
            $product->stock = $product->stock - $details->quantity;
            $product->save();
             }
        }




    //      $product= Product::find($orderDetail->product_id);   
    //    $orderDetail->unit_price=$product->price;
    //    $order = Order::find($orderDetail->order_id);
    //     if(!$order->customer_id==null){
    //         $customer = Customer::find($order->customer_id);
    //         $customer->points= $customer->points+($product->points*$orderDetail->quantity);
    //         $customer->save();
    //     }




    }


}
