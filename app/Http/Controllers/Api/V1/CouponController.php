<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomerCoupon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    /**
     * Validar cupón y calcular descuento sobre un subtotal
     */
    public function validateCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'coupon_id' => 'required|integer',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $coupon = CustomerCoupon::where('id', $validated['coupon_id'])
                ->where('customer_id', $request->user()->id)
                ->where('status', 1)
                ->with('coupons')
                ->first();

            if (!$coupon) {
                return response()->json([
                    'message' => 'Cupón no disponible',
                    'errors' => ['coupon_id' => ['El cupón no pertenece al cliente o ya fue utilizado']],
                ], 404);
            }

            $subtotal = (float) $validated['subtotal'];
            $discountPercent = (int) $coupon->discount;
            $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
            $total = max(round($subtotal - $discountAmount, 2), 0);

            return response()->json([
                'data' => [
                    'coupon_id' => $coupon->id,
                    'coupon_name' => $coupon->coupons?->name,
                    'discount_percent' => $discountPercent,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total' => $total,
                ],
                'message' => 'Cupón válido',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
