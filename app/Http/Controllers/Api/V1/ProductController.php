<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProductShowRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar productos con paginación y filtros
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'search' => ['nullable', 'string', 'max:120'],
        ]);

        $perPage = $validated['per_page'] ?? 15;
        $categoryId = $validated['category_id'] ?? null;
        $search = $validated['search'] ?? null;

        $query = Product::with('category')->whereNull('deleted_at');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products->items()),
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
    public function show(ProductShowRequest $request, int $id)
    {
        $product = Product::where('id', $id)
            ->whereNull('deleted_at')
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Producto no encontrado',
                'errors' => ['product' => ['El producto solicitado no existe']],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
            'message' => 'Producto obtenido',
        ], 200);
    }
}
