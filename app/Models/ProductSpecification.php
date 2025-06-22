<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductSpecification extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     */
    protected $fillable = [
        'product_id',
        'spec_key',
        'spec_name',
        'spec_value',
        'group',
        'sort_order',
        'is_filterable'
    ];

    protected $casts = [
        'is_filterable' => 'boolean'
    ];

    /**
     * Получить товар, которому принадлежит эта характеристика
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Получить все доступные для фильтрации характеристики для категории
     */
    public static function getFilterableSpecsByCategory($categoryId)
    {
        // Добавим логирование для отладки
        Log::info('Getting filterable specs for category: ' . $categoryId);
        
        // Если у нас есть категория, собираем все ID этой категории и её дочерних категорий
        $category = Category::with('children')->find($categoryId);
        
        if (!$category) {
            Log::warning('Category not found: ' . $categoryId);
            return [];
        }
        
        // Собираем ID всех подкатегорий
        $categoryIds = [$category->id];
        if ($category->children->count() > 0) {
            $childCategoryIds = $category->children->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childCategoryIds);
        }
        
        Log::info('Category IDs for filtering: ' . implode(', ', $categoryIds));
        
        // Находим ID товаров из указанных категорий
        $productIds = Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
        
        if (empty($productIds)) {
            Log::warning('No products found for categories: ' . implode(', ', $categoryIds));
            return [];
        }
        
        Log::info('Found ' . count($productIds) . ' products for filtering');
        
        // Получаем все характеристики для этих товаров
        return self::whereIn('product_id', $productIds)
            ->where('is_filterable', true)
            ->select('spec_key', 'spec_name', 'group')
            ->distinct()
            ->get();
    }

    /**
     * Получить все значения для конкретной характеристики в указанной категории
     */
    public static function getSpecValuesByCategory($specKey, $categoryId)
    {
        // Если у нас есть категория, собираем все ID этой категории и её дочерних категорий
        $category = Category::with('children')->find($categoryId);
        
        if (!$category) {
            return [];
        }
        
        // Собираем ID всех подкатегорий
        $categoryIds = [$category->id];
        if ($category->children->count() > 0) {
            $childCategoryIds = $category->children->pluck('id')->toArray();
            $categoryIds = array_merge($categoryIds, $childCategoryIds);
        }
        
        // Находим ID товаров из указанных категорий
        $productIds = Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
        
        if (empty($productIds)) {
            return [];
        }
        
        // Получаем все уникальные значения для указанного ключа
        return self::whereIn('product_id', $productIds)
            ->where('spec_key', $specKey)
            ->select('spec_value')
            ->distinct()
            ->pluck('spec_value')
            ->toArray();
    }
} 