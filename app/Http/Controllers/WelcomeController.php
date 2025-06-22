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

        // Получаем категории для секции "Популярные категории" и "Акции и скидки" (случайные)
        $categories = Category::whereNull('parent_id')->inRandomOrder()->take(5)->get();

        // Получаем категории для секции "Сезонные товары" (по конкретным названиям)
        $seasonalCategories = Category::whereIn('name', ['Корпуса', 'Системы охлаждения'])->get();

        return view('welcome', compact('products', 'topCategoriesProducts', 'categories', 'seasonalCategories'));
    }
}