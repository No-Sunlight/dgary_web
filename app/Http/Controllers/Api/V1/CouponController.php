<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CouponValidateRequest;
use App\Http\Resources\Api\V1\CouponResource;
use App\Http\Resources\Api\V1\CouponValidationResource;
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
            'success' => true,
            'data' => CouponResource::collection($coupons),
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
                'success' => false,
                'data' => null,
                'message' => 'Cupón no encontrado',
                'errors' => ['coupon' => ['El cupón solicitado no existe']],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CouponResource($coupon),
            'message' => 'Cupón obtenido',
        ], 200);
    }

    /**
     * Validar cupón y calcular descuento sobre un subtotal
     */
    public function validateCoupon(CouponValidateRequest $request)
    {
        $validated = $request->validated();

        $coupon = CustomerCoupon::where('id', $validated['coupon_id'])
            ->where('customer_id', $request->user()->id)
            ->where('status', 1)
            ->with('coupons')
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Cupón no disponible',
                'errors' => ['coupon_id' => ['El cupón no pertenece al cliente o ya fue utilizado']],
            ], 404);
        }

        $subtotal = (float) $validated['subtotal'];
        $discountPercent = (int) $coupon->discount;
        $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
        $total = max(round($subtotal - $discountAmount, 2), 0);

        return response()->json([
            'success' => true,
            'data' => new CouponValidationResource([
                'coupon_id' => $coupon->id,
                'coupon_name' => $coupon->coupons?->name,
                'discount_percent' => $discountPercent,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
            ]),
            'message' => 'Cupón válido',
        ], 200);
    }
}
