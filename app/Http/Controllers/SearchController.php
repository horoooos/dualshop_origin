<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        
        Log::info('Поиск через форму', ['query' => $query, 'category' => $category]);
        
        $products = Product::where('qty', '>', 0)
            ->when($query, function($q) use ($query) {
                return $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($category, function($q) use ($category) {
                return $q->where('product_type', $category);
            })
            ->paginate(20);
            
        $categories = Category::all();
        $params = collect($request->query());
        
        Log::info('Результаты поиска через форму', ['count' => $products->count()]);
        
        // Перенаправляем на каталог вместо отдельного представления search
        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'params' => $params,
            'searchQuery' => $query,
            'searchResults' => true,
            'currentCategory' => null,
            'filters' => [],
            'appliedFilters' => []
        ]);
    }
} 