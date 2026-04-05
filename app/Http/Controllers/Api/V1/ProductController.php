<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar productos con paginación y filtros
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        $query = Product::with('category')->whereNull('deleted_at');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->paginate($perPage);

        $normalized = collect($products->items())->map(function (Product $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category?->name,
                'price' => (float) $product->price,
                'estimatedTime' => '10-25 min Estimación',
                'imageUrl' => $product->image,
            ];
        })->values();

        return response()->json([
            'data' => $normalized,
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
            'message' => 'Productos obtenidos',
        ], 200);
    }

    /**
     * Obtener detalles de un producto
     */
    public function show($id)
    {
        $product = Product::where('id', $id)
            ->whereNull('deleted_at')
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'errors' => ['product' => ['El producto solicitado no existe']],
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category?->name,
                'price' => (float) $product->price,
                'estimatedTime' => '10-25 min Estimación',
                'imageUrl' => $product->image,
            ],
            'message' => 'Producto obtenido',
        ], 200);
    }
}
