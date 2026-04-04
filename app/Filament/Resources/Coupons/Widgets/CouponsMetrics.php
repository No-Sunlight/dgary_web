<?php

namespace App\Filament\Resources\Coupons\Widgets;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CouponsMetrics extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        //Cantidad de cupones activos
        $Cuponesactivos=Coupon::where('status',1)->count();    

        //Cupon mas popular
        $coupon_stats=CustomerCoupon::query()
           // ->join('coupons', 'CustomerCoupon.id', '=', 'order_details.order_id')
             ->selectRaw('coupon_id, count(*) as popularity')
             ->groupBy('coupon_id')
             ->orderByDesc('popularity')
             ->first();
         $coupon = Coupon::find($coupon_stats->coupon_id);    

        //Metricas basadas en fecha
         $startDate =Carbon::now()->startOfMonth();
         $endDate = $startDate->copy()->endOfMonth();

        $burnt_points = CustomerCoupon::where('created_at',1)->count();    




        //Puntos 


        return [
            Stat::make('Cupones activos',$Cuponesactivos)
                ->description("Disponibles para canjear")
                ->descriptionIcon(Heroicon::Ticket)
                ->color( 'success'),

             Stat::make('Cupon mas canjeado',$coupon->name)
             ->description('Descuento: '.$coupon->discount.' Precio: '.$coupon->points_price)   
                ->descriptionIcon(Heroicon::OutlinedTrophy)


                
                    
        ];


    }
}
