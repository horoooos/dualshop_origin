<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductsSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            $product->rating = rand(30, 50) / 10; // Рейтинг от 3.0 до 5.0
            $product->is_seasonal = (bool)rand(0, 1);
            $product->save();
        }
    }
} 