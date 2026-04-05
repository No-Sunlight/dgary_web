<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Listar categorías disponibles
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories,
            'message' => 'Categorías obtenidas exitosamente',
        ], 200);
    }

    /**
     * Obtener una categoría con sus productos
     */
    public function show($id)
    {
        $category = Category::with('Products')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Categoría no encontrada',
                'errors' => ['category' => ['La categoría solicitada no existe']],
            ], 404);
        }

        return response()->json([
            'data' => $category,
            'message' => 'Categoría obtenida',
        ], 200);
    }
}
