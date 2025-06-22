<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    /**
     * Отображает страницу каталога с фильтрацией
     */
    public function index(Request $request, $categoryId = null)
    {
        $query = Product::query()->select('products.*')->with(['category', 'specifications', 'images']);
        
        // Фильтрация по категории
        // Если $categoryId не передан через URL, пробуем взять из query string
        $categoryId = $categoryId ?? $request->input('category');
        $currentCategory = null;
        
        if ($categoryId) {
            $currentCategory = Category::find($categoryId);
            
            if ($currentCategory) {
                // Если категория имеет подкатегории, показываем товары из всех подкатегорий
                if ($currentCategory->children->count() > 0) {
                    $childCategoryIds = $currentCategory->children->pluck('id')->toArray();
                    $query->whereIn('category_id', array_merge([$categoryId], $childCategoryIds));
                } else {
                    $query->where('category_id', $categoryId);
                }
            }
        }
        
        // Фильтрация по характеристикам товаров
        $appliedFilters = $request->input('filter', []);
        
        // Логируем примененные фильтры
        Log::info('Примененные фильтры спецификаций:', $appliedFilters);

        if (!empty($appliedFilters) && is_array($appliedFilters)) {
            foreach ($appliedFilters as $specKey => $specValues) {
                if (!is_array($specValues)) {
                    $specValues = [$specValues];
                }
                // Фильтрация по материалу: ищем по вхождению (LIKE) и нормализуем
                if ($specKey === 'material') {
                    $query->whereHas('specifications', function($q) use ($specValues) {
                        $q->where('spec_key', 'material');
                        $q->where(function($subQ) use ($specValues) {
                            foreach ($specValues as $val) {
                                $val = trim(mb_strtolower($val));
                                $subQ->orWhereRaw('LOWER(REPLACE(REPLACE(spec_value, ", ", ","), ",", ", ")) LIKE ?', ["%$val%"]);
                            }
                        });
                    });
                    continue;
                }
                // Остальные фильтры как раньше
                $query->whereHas('specifications', function($q) use ($specKey, $specValues) {
                    $q->where('spec_key', $specKey);
                    $q->where(function($subQ) use ($specValues) {
                        foreach ($specValues as $val) {
                            $val = trim(mb_strtoupper($val));
                            $subQ->orWhereRaw('UPPER(TRIM(spec_value)) = ?', [$val]);
                        }
                    });
                });
            }
        }
        
        // Логируем сформированный запрос перед его выполнением
        Log::info('Запрос к товарам перед выполнением:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Фильтрация по цене
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        
        if ($priceMin) {
            $query->where('price', '>=', $priceMin);
        }
        
        if ($priceMax) {
            $query->where('price', '<=', $priceMax);
        }
        
        // Фильтрация по наличию
        $inStock = $request->input('in_stock');
        if ($inStock) {
            $query->where('in_stock', true);
        }
        
        // Фильтрация по акциям и статусам
        if ($request->input('on_sale')) {
            $query->where('on_sale', true);
        }
        
        if ($request->input('credit_available')) {
            $query->where('credit_available', true);
        }
        
        if ($request->input('bestseller')) {
            $query->where('is_bestseller', true);
        }
        
        if ($request->input('new')) {
            $query->where('is_new', true);
        }
        
        // *** Вычисление максимальной цены для слайдера (для текущей категории) ***
        // Создаем новый запрос, учитывающий только фильтр по категории
        $queryForMaxPrice = Product::query();
        if ($categoryId) {
             // Применяем ту же логику фильтрации по категории, что и для основного запроса
             if ($currentCategory) {
                 if ($currentCategory->children->count() > 0) {
                     $childCategoryIds = $currentCategory->children->pluck('id')->toArray();
                     $queryForMaxPrice->whereIn('category_id', array_merge([$categoryId], $childCategoryIds));
                 } else {
                     $queryForMaxPrice->where('category_id', $categoryId);
                 }
             }
         }
        
        // Вычисляем максимальную цену среди товаров в текущей категории (без других фильтров)
        $maxProductPrice = $queryForMaxPrice->max('price');

         // Если нет товаров в категории (или max price некорректен), установим разумное значение по умолчанию
         if (empty($maxProductPrice) || !is_numeric($maxProductPrice) || $maxProductPrice <= 0) {
              // Устанавливаем значение по умолчанию, которое соответствует надежному maxPrice в JS
             $maxProductPrice = 1000000; // Должно совпадать с maxPriceDefault в JS
         }
        
        // Сортировка
        $sort = $request->input('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'discount_desc':
                $query->orderBy('discount_percent', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'country_asc':
                $query->orderBy('country', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        // Получаем товары без пагинации
        $products = $query->get();
        
        // Получение всех категорий для меню
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        
        // --- Логика получения доступных фильтров --- //
        $availableFilters = [];

        // Определяем набор productIds для получения доступных фильтров
        // Он должен основываться на ВСЕХ товарах в текущей категории (или всех товарах, если категория не выбрана),
        // а не на отфильтрованных товарах ($products)
        $filterProductIds = [];
        if ($currentCategory) {
            $categoryIds = [$currentCategory->id];
            if ($currentCategory->children->count() > 0) {
                $categoryIds = array_merge($categoryIds, $currentCategory->children->pluck('id')->toArray());
            }
            $filterProductIds = Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
        } else {
            // Если категория не выбрана, получаем ID всех товаров
            $filterProductIds = Product::pluck('id')->toArray();
        }

        if (!empty($filterProductIds)) {
            // Для корпусов разрешаем все характеристики, а не только allowedSpecKeys
            $allowedKeys = null;
            if ($currentCategory) {
                if ($currentCategory->name !== 'Корпуса') {
                    $allowedKeys = $currentCategory->allowedSpecKeys();
                }
            }
            // Получаем уникальные ключи спецификаций, учитывая allowedKeys, если они есть
            $uniqueSpecKeysQuery = DB::table('product_specifications')
                ->whereIn('product_id', $filterProductIds)
                ->where('is_filterable', true);

            if ($allowedKeys !== null && !empty($allowedKeys)) {
                 $uniqueSpecKeysQuery->whereIn('spec_key', $allowedKeys);
            }

            $uniqueSpecKeys = $uniqueSpecKeysQuery->select('spec_key')->distinct()->pluck('spec_key')->toArray();

            // --- Бренды: объединяем ARDOR и ARDOR GAMING ---
            $brandValues = DB::table('product_specifications')
                ->whereIn('product_id', $filterProductIds)
                ->where('spec_key', 'brand')
                ->pluck('spec_value');

            Log::info('Raw brand values from DB:', $brandValues->toArray());

            $brandValues = $brandValues->map(function($v) {
                    $v = trim(mb_strtoupper($v));
                    if (strpos($v, 'ARDOR') !== false) return 'ARDOR GAMING';
                    return $v;
                })
                ->unique()
                ->filter(fn($v) => $v !== 'КОРПУС' && $v !== 'ЕСТЬ')
                ->values()
                ->toArray();
            if (!empty($brandValues)) {
                $availableFilters['Основные характеристики']['brand'] = [
                    'name' => 'Бренд',
                    'values' => $brandValues
                ];
            }

            // Остальные фильтры как раньше
            foreach ($uniqueSpecKeys as $key) {
                if ($key === 'brand') continue; // уже добавили выше
                // Не добавлять фильтр по цвету для видеокарт (сохраняем существующее исключение)
                if ($currentCategory && $currentCategory->name === 'Видеокарты' && $key === 'color') {
                    continue;
                }
                // Для блоков питания фильтр по цвету только по полю color из products
                if ($currentCategory && $currentCategory->name === 'Блоки питания' && $key === 'color') {
                    // Жестко фильтруем только реальные цвета для блоков питания
                    $values = ['Черный', 'Серый'];
                    $specInfo = DB::table('product_specifications')
                        ->where('spec_key', $key)
                        ->whereIn('product_id', $filterProductIds)
                        ->select('spec_name', 'group')
                        ->first();
                    $name = $specInfo ? $specInfo->spec_name : 'Цвет';
                    $group = $specInfo ? ($specInfo->group ?? 'Внешний вид') : 'Внешний вид';
                    $availableFilters[$group][$key] = [
                        'name' => $name,
                        'values' => $values
                    ];
                    continue;
                }

                // Получаем информацию о спецификации (имя, группа)
                $specInfo = DB::table('product_specifications')
                    ->where('spec_key', $key)
                    // Ищем информацию о спецификации среди всех товаров, а не только фильтрованных
                    ->whereIn('product_id', $filterProductIds)
                    ->select('spec_name', 'group')
                    ->first();

                if ($specInfo) {
                    $name = $specInfo->spec_name;
                    $group = $specInfo->group ?? 'Основные характеристики';

                    // Получаем уникальные значения для этого ключа, исключая 'Корпус' (сохраняем существующее исключение)
                    $values = DB::table('product_specifications')
                        ->whereIn('product_id', $filterProductIds)
                        ->where('spec_key', $key)
                        ->where('spec_value', '!=', 'Корпус')
                        ->pluck('spec_value')
                        ->map(function($v) use ($key) {
                            if ($key === 'brand') {
                                $v = trim(mb_strtoupper($v));
                                if (strpos($v, 'ARDOR') !== false) return 'ARDOR GAMING';
                            }
                            if ($key === 'material') {
                                $v = trim(mb_strtolower($v));
                                $v = str_replace([' ,', ', '], ',', $v);
                                $v = str_replace(',', ', ', $v);
                                $v = ucfirst($v);
                            }
                            return $v;
                        })
                        ->unique()
                        ->filter(fn($v) => $v !== 'есть')
                        ->values()
                        ->toArray();

                    if (!empty($values)) {
                         // Сортируем значения фильтра по алфавиту
                         sort($values);

                        $availableFilters[$group][$key] = [
                            'name' => $name,
                            'values' => $values
                        ];
                    }
                }
            }
             // Сортируем группы фильтров по ключу группы
             ksort($availableFilters);
        }
        // --- Конец логики получения доступных фильтров --- //

        $params = collect($request->query());

        // Определяем минимальную цену (обычно 0)
        $minPrice = 0;

        // Передаем доступные фильтры в представление
        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'filters' => $availableFilters, // Передаем полные доступные фильтры
            'appliedFilters' => $appliedFilters,
            'params' => $params,
            'searchResults' => false,
            'maxProductPrice' => $maxProductPrice, // Передаем максимальную цену в шаблон
            'minPrice' => $minPrice // Передаем минимальную цену в шаблон
        ]);
    }

    /**
     * Отображает страницу категорий
     */
    public function categories()
    {
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
//        dd($categories->pluck('name')); // Временно добавлено для отладки
        return view('catalog.categories', compact('categories'));
    }

    /**
     * Отображает страницу поиска по каталогу
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $productsQuery = Product::query();
        
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }
        
        // Применяем фильтры
        $categoryId = $request->input('category');
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        $products = $productsQuery->get();
        
        // Return JSON response instead of a view
        return response()->json($products);
    }
    
    /**
     * Отображает страницу товара
     */
    public function show($id)
    {
        $product = Product::with(['category', 'specifications', 'images'])->findOrFail($id);
        $allowedKeys = $product->category ? $product->category->allowedSpecKeys() : [];
        // Оставляем только одну характеристику по каждому ключу (spec_key), даже если они в разных группах
        $uniqueSpecs = [];
        foreach ($product->specifications->whereIn('spec_key', $allowedKeys) as $spec) {
            if (!isset($uniqueSpecs[$spec->spec_key])) {
                $uniqueSpecs[$spec->spec_key] = $spec;
            }
        }
        $groupedSpecs = collect($uniqueSpecs)->groupBy('group');
        // Получаем похожие товары из той же категории
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        // Получаем рекомендации по категории (другие товары, не совпадающие с текущим и не входящие в similarProducts)
        $recommendedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereNotIn('id', $similarProducts->pluck('id'))
            ->where('in_stock', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();
        // Если рекомендаций нет, показываем любые товары из категории (даже если они уже в similarProducts или не in_stock)
        if ($recommendedProducts->count() === 0) {
            $recommendedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->limit(8)
                ->get();
        }
        // Debug-лог
        \Log::info('Recommended products count: ' . $recommendedProducts->count() . ' for product ' . $product->id);
        // Получение всех категорий для меню
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('catalog.show', [
            'product' => $product,
            'groupedSpecs' => $groupedSpecs,
            'relatedProducts' => $similarProducts,
            'recommendedProducts' => $recommendedProducts,
            'categories' => $categories
        ]);
    }
}
