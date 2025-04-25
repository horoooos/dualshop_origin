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
    public function index(Request $request)
    {
        try {
            Log::info('Starting WelcomeController index method');
            
            // Получаем все категории
            $categories = Category::all();
            
            Log::info('Categories retrieved:', [
                'count' => $categories->count(),
                'categories' => $categories->toArray()
            ]);
            
            // Получаем все товары с нужными полями
            $products = DB::table('products')
                ->select('id', 'title', 'price', 'img', 'description', 'qty')
                ->get();
            
            // Получаем последний добавленный товар
            $latestProduct = Product::latest()->first();

            // Получаем сезонные товары
            $seasonalProducts = Product::whereHas('category', function($query) {
                $query->where('is_seasonal', true);
            })->latest()->take(5)->get();

            // Получаем товары со скидками
            $promotionProducts = Product::where('discount', '>', 0)
                ->latest()
                ->take(5)
                ->get();

            // Получаем популярные товары
            $topCategoriesProducts = Product::orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Логируем все данные перед отправкой в представление
            Log::info('Data being sent to welcome view:', [
                'categories_count' => $categories->count(),
                'products_count' => $products->count(),
                'latestProduct' => $latestProduct ? 'exists' : 'null',
                'seasonalProducts_count' => $seasonalProducts->count(),
                'promotionProducts_count' => $promotionProducts->count(),
                'topCategoriesProducts_count' => $topCategoriesProducts->count()
            ]);

            return view('welcome', compact(
                'categories',
                'products',
                'latestProduct',
                'seasonalProducts',
                'promotionProducts',
                'topCategoriesProducts'
            ));
        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Error in WelcomeController:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // В случае ошибки возвращаем пустые данные
            return view('welcome', [
                'categories' => collect(),
                'products' => collect(),
                'latestProduct' => null,
                'seasonalProducts' => collect(),
                'promotionProducts' => collect(),
                'topCategoriesProducts' => collect()
            ]);
        }
    }
}