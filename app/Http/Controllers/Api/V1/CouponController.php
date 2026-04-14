<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CouponValidateRequest;
use App\Http\Resources\Api\V1\CouponCatalogResource;
use App\Http\Resources\Api\V1\CouponValidationResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Listar cupones disponibles del cliente
     */
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->active()
            ->orderBy('points_price')
            ->get();

        return response()->json([
            'success' => true,
            'data' => CouponCatalogResource::collection($coupons),
            'message' => 'Cupones disponibles',
        ], 200);
    }

    /**
     * Obtener detalles de un cupón
     */
    public function show($id, Request $request)
    {
        $coupon = Coupon::query()
            ->active()
            ->where('id', $id)
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
            'data' => new CouponCatalogResource($coupon),
            'message' => 'Cupón obtenido',
        ], 200);
    }

    /**
     * Validar cupón y calcular descuento sobre un subtotal
     */
    public function validateCoupon(CouponValidateRequest $request)
    {
        $validated = $request->validated();

        $couponReference = (string) $validated['coupon_id'];
        $coupon = Coupon::query()
            ->active()
            ->when(
                ctype_digit($couponReference),
                fn ($query) => $query->where('id', (int) $couponReference),
                fn ($query) => $query->where('code', $couponReference)
            )
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Cupón no disponible',
                'errors' => ['coupon_id' => ['El cupón no es válido o no está activo']],
            ], 404);
        }

        $subtotal = (float) $validated['subtotal'];
        $discountAmount = round(min($subtotal, (float) $coupon->discount), 2);
        $total = max(round($subtotal - $discountAmount, 2), 0);

        return response()->json([
            'success' => true,
            'data' => new CouponValidationResource([
                'coupon_id' => $coupon->id,
                'coupon_name' => $coupon->name,
                'discount_percent' => 0,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
            ]),
            'message' => 'Cupón válido',
        ], 200);
    }
}
