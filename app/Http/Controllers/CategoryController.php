<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Получить все категории с подкатегориями
     */
    public function getAllCategories()
    {
        // Получаем только родительские категории (без parent_id)
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();
            
        return response()->json($categories);
    }
    
    /**
     * Получить категории по типу продукта
     */
    public function getCategoriesByType($type)
    {
        $categories = Category::where('product_type', $type)
            ->with('children')
            ->get();
            
        return response()->json($categories);
    }
    
    /**
     * Получить количество продуктов в категории
     */
    public function getCategoryProductCount($id)
    {
        $category = Category::findOrFail($id);
        $count = $category->products()->count();
        
        return response()->json(['count' => $count]);
    }
} 