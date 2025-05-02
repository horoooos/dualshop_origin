<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    public function up()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            $product->rating = rand(30, 50) / 10; // Рейтинг от 3.0 до 5.0
            $product->is_seasonal = (bool)rand(0, 1);
            $product->save();
        }
    }

    public function down()
    {
        Product::query()->update([
            'rating' => 0,
            'is_seasonal' => false
        ]);
    }
}; 