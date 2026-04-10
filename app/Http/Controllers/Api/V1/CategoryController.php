<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoryShowRequest;
use App\Http\Resources\Api\V1\CategoryDetailResource;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Listar categorías disponibles
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'message' => 'Categorías obtenidas exitosamente',
        ], 200);
    }

    /**
     * Obtener una categoría con sus productos
     */
    public function show(CategoryShowRequest $request, int $id)
    {
        $category = Category::with('Products.category')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Categoría no encontrada',
                'errors' => ['category' => ['La categoría solicitada no existe']],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CategoryDetailResource($category),
            'message' => 'Categoría obtenida',
        ], 200);
    }
}
