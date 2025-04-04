<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index()
    {
        // Получаем популярные категории (родительские с дочерними)
        $popularCategories = Category::with(['children' => function($query) {
                $query->limit(5); // Ограничиваем количество подкатегорий
            }])
            ->whereNull('parent_id')
            ->orderByDesc('views')
            ->limit(3)
            ->get();
    
        // Сезонные товары
        $seasonalProducts = Product::where('is_seasonal', true)
            ->with('category')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();
    
        // Товары со скидками
        $discountedProducts = Product::where('discount', '>', 0)
            ->with('category')
            ->orderByDesc('discount')
            ->limit(3)
            ->get();
    
        return view('home', compact(
            'popularCategories',
            'seasonalProducts',
            'discountedProducts'
        ));
    }
} 