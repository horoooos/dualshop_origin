<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class AboutController extends Controller
{
// AboutController.php
public function index()
{
    $products = DB::table("products")
        ->select(['id', 'img', 'title'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $newProducts = Product::orderBy('created_at', 'desc')
        ->take(4)
        ->get();

    // Получаем просто 3 любых товара вместо сезонных
    $seasonalProducts = Product::take(3)->get();

    // Получаем 3 любых товара вместо акционных
    $promotionProducts = Product::take(3)->get();

    return view('welcome', [
        'products' => $products,
        'newProducts' => $newProducts,
        'seasonalProducts' => $seasonalProducts,
        'promotionProducts' => $promotionProducts
    ]);
}
}
