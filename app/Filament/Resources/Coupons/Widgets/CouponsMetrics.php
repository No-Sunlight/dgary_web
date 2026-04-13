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
    // Cantidad de cupones activos
    $Cuponesactivos = Coupon::where('status', 1)->count();

    // Cupon mas popular
    $coupon_stats = CustomerCoupon::query()
        ->selectRaw('coupon_id, count(*) as popularity')
        ->groupBy('coupon_id')
        ->orderByDesc('popularity')
        ->first();

    // We only look for the coupon if stats exist, otherwise it stays null
    $coupon = $coupon_stats ? Coupon::find($coupon_stats->coupon_id) : null;

    return [
        Stat::make('Cupones activos', $Cuponesactivos)
            ->description("Disponibles para canjear")
            ->descriptionIcon('heroicon-m-ticket')
            ->color('success'),

        Stat::make('Cupon mas canjeado', $coupon?->name ?? 'Ninguno')
            ->description($coupon 
                ? "Descuento: {$coupon->discount} | Precio: {$coupon->points_price}" 
                : "Sin canjes registrados")
            ->descriptionIcon('heroicon-m-trophy')
            ->color($coupon ? 'warning' : 'gray'),
    ];
}
}
