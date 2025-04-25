<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {

    public function index()
    {
        try {
            // Получаем все категории
            $categories = DB::table('categories')
                ->select('id', 'product_type')
                ->get();

            // Получаем последний добавленный товар
            $latestProduct = DB::table('products')
                ->select('id', 'img', 'title')
                ->orderBy('created_at', 'desc')
                ->first();

            // Получаем сезонные товары
            $seasonalProducts = DB::table('products')
                ->select('id', 'img', 'title')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Получаем товары со скидками
            $promotionProducts = DB::table('products')
                ->select('id', 'img', 'title')
                ->where('discount', '>', 0)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Получаем популярные товары
            $topCategoriesProducts = DB::table('products')
                ->select('id', 'img', 'title', 'price')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Получаем все товары
            $products = DB::table('products')
                ->select('id', 'img', 'title')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            return view('welcome', compact(
                'categories',
                'latestProduct',
                'seasonalProducts',
                'promotionProducts',
                'topCategoriesProducts',
                'products'
            ));

        } catch (\Exception $e) {
            // В случае ошибки возвращаем пустые данные
            return view('welcome', [
                'categories' => collect(),
                'latestProduct' => null,
                'seasonalProducts' => collect(),
                'promotionProducts' => collect(),
                'topCategoriesProducts' => collect(),
                'products' => collect()
            ]);
        }
    }
} 