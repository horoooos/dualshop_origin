<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        
        $products = Product::where('qty', '>', 0)
            ->when($query, function($q) use ($query) {
                return $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($category, function($q) use ($category) {
                return $q->where('product_type', $category);
            })
            ->get();
            
        $categories = Category::all();
        
        return view('search', [
            'products' => $products,
            'categories' => $categories,
            'query' => $query,
            'selectedCategory' => $category
        ]);
    }
} 