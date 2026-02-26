<?php

namespace App\Observers;

use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class DetailObserver
{
    /**
     * Handle the OrderDetail "created" event.
     */
    public function creating(OrderDetail $orderDetail): void
    {
       $product= Product::find($orderDetail->product_id);   
       $orderDetail->unit_price=$product->price;
       $order = Order::find($orderDetail->order_id);
        if(!$order->customer_id==null){
            $customer = Customer::find($order->customer_id);
            $customer->points= $customer->points+($product->points*$orderDetail->quantity);
            $customer->save();
        }

    }

    /**
     * Handle the OrderDetail "updated" event.
     */
    public function updated(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "deleted" event.
     */
    public function deleted(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "restored" event.
     */
    public function restored(OrderDetail $orderDetail): void
    {
        //
    }

    /**
     * Handle the OrderDetail "force deleted" event.
     */
    public function forceDeleted(OrderDetail $orderDetail): void
    {
        //
    }
}
