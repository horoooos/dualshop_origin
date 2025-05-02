<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;


class WelcomeController extends Controller
{
    public function index()
    {
        // Получаем все продукты с категориями одним запросом
        $products = Product::with('category')
            ->where('qty', '>', 0)
            ->select('id', 'title', 'price', 'img', 'description', 'qty', 'category_id', 'rating', 'is_seasonal')
            ->take(6)
            ->get();

        // Получаем популярные товары
        $topCategoriesProducts = Product::with('category')
            ->where('qty', '>', 0)
            ->orderBy('rating', 'desc')
            ->select('id', 'title', 'price', 'img', 'description', 'qty', 'category_id', 'rating', 'is_seasonal')
            ->take(8)
            ->get();

        // Получаем категории
        $categories = Category::all();

        return view('welcome', compact('products', 'topCategoriesProducts', 'categories'));
    }
}