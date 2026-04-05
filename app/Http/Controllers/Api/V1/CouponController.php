<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Listar cupones disponibles del cliente
     */
    public function index(Request $request)
    {
        $coupons = CustomerCoupon::where('customer_id', $request->user()->id)
            ->where('status', 1)
            ->with('coupons')
            ->get();

        return response()->json([
            'data' => $coupons,
            'message' => 'Cupones disponibles',
        ], 200);
    }

    /**
     * Obtener detalles de un cupón
     */
    public function show($id, Request $request)
    {
        $coupon = CustomerCoupon::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->with('coupons')
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'Cupón no encontrado',
                'errors' => ['coupon' => ['El cupón solicitado no existe']],
            ], 404);
        }

        return response()->json([
            'data' => $coupon,
            'message' => 'Cupón obtenido',
        ], 200);
    }
}
