<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nieves',
                'description' => 'Deliciosas nieves y helados en variedad de sabores',
            ],
            [
                'name' => 'Productos de Leche',
                'description' => 'Láseres y productos derivados de leche',
            ],
            [
                'name' => 'Productos de Agua',
                'description' => 'Paletas de agua y postres refrescantes',
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Bebidas frías y refrescos',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
