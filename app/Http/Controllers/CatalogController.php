<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('qty', '>', 0);
        $categories = Category::all();
        $params = collect($request->query());

        if ($params->get('sort_by')) {
            $products = $products->orderBy($params->get('sort_by'));
        }
        if ($params->get('sort_by_desc')) {
            $products = $products->orderByDesc($params->get('sort_by_desc'));
        }
        if ($params->get('filter')) {
            $products = $products->where('product_type', $params->get('filter'));
        }

        $products = $products->get();

        return view('catalog', [
            'products' => $products, 
            'categories' => $categories, 
            'params' => $params
        ]);
    }
    
    public function category(Request $request, $category)
    {
        $products = Product::where('qty', '>', 0)
            ->where('product_type', $category);
            
        $categories = Category::all();
        $params = collect($request->query());
        
        if ($params->get('sort_by')) {
            $products = $products->orderBy($params->get('sort_by'));
        }
        if ($params->get('sort_by_desc')) {
            $products = $products->orderByDesc($params->get('sort_by_desc'));
        }
        
        $products = $products->get();
        
        return view('catalog', [
            'products' => $products, 
            'categories' => $categories, 
            'params' => $params,
            'currentCategory' => $category
        ]);
    }
}
