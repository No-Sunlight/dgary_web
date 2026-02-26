<?php

namespace App\Observers;

use App\Filament\Resources\Products\Tables\ProductsTable;
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
