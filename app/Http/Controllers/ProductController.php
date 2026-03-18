<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
        public function index()
    {
        $products = Product::all();

        return response()->json([
            'Products' => $products
        ]);
    }
 
    public function store(Request $request)
    {
        // ...
    }
 
    public function show(Product $user)
    {
        // ...
    }
 
    public function update(Request $request, Product $user)
    {
        // ...
    }
 
    public function destroy(Product $user)
    {
        // ...
    }
}
