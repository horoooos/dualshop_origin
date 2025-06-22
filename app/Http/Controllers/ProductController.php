<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ProductSpecification;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class
ProductController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        
        // Используем Eloquent модель Product для загрузки продукта
        // Загружаем отношения images и category, так как они используются в шаблоне product.blade.php
        $product = Product::with(['category', 'images'])->find($id);

        if (!is_null($product)) {
            // Поле in_stock будет вычислено аксессором модели Product
            // Поле on_sale может быть вычислено аксессором или определяться наличием discount_percent > 0
            // Если в вашей модели Product есть аксессор getOnSaleAttribute, убедитесь, что он корректно работает с discount_percent
            
            // Если у вас нет аксессора in_stock в модели Product, можно добавить его:
            // public function getInStockAttribute()
            // {
            //     return $this->qty > 0;
            // }
            
            // Если у вас нет аксессора on_sale в модели Product, можно добавить его:
            // public function getOnSaleAttribute()
            // {
            //     return ($this->discount_percent ?? 0) > 0;
            // }
            
            return view('product', ['product' => $product]);
        } else {
            return view('404');
        }
    }

    public function getProducts(Request $request)
    {
        // Используем Eloquent модель Product и загружаем отношения images и category
        $products = \App\Models\Product::with('images', 'category')
                    ->select('products.*', 'categories.name as category_name') // Выбираем поля, включая category_name через join
                    ->join('categories', 'categories.id', '=', 'products.category_id') // Присоединяем категории для category_name
                    ->get(); // Returns App\Models\Product instances with relationships loaded

        // Проходим по каждому продукту, чтобы добавить category_name в основной объект для совместимости с текущим шаблоном
        // Это может быть неоптимально, лучше изменить шаблон, чтобы использовать $product->category->name
        // Но для быстрой совместимости с текущим шаблоном сделаем так
        $products->each(function($product) {
            // category_name уже выбрано в запросе с помощью select и join
        });

        return view('admin.products', ['products' => $products]);
    }

    public function getProductById($id)
    {
        $categories = DB::table('categories')->get();
        $product = DB::table('products')->join(
            'categories',
            'categories.id',
            '=',
            'products.category_id'
        )->select(
            'products.id as id',
            'products.*',
            'categories.name as category_name'
        )->where('products.id', $id)->first();

        if (!is_null($product)) {
            $product->category_id = $product->category_id;
            return view('admin.product-edit', ['categories' => $categories, 'product' => $product]);
        } else {
            return redirect()->route('admin.products')->with('error', 'Товар не найден!');
        }
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.product-edit', compact('product', 'categories'));
    }

    public function editProduct(Request $request, $id)
    {
        // dd('Reached editProduct method', $id);
        // dd('Before validation', $request->all(), $id);
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'qty' => 'required|integer|min:0',
            'color' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'country' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'specifications' => 'nullable|array',
            'specifications.*.id' => 'nullable|integer|exists:product_specifications,id',
            'specifications.*.spec_name' => 'required_with:specifications|string|max:255',
            'specifications.*.spec_value' => 'required_with:specifications|string|max:255',
            'specifications.*.group' => 'nullable|string|max:255',
            'specifications.*.is_filterable' => 'nullable|boolean',
            'specifications_to_delete' => 'nullable|array',
            'specifications_to_delete.*' => 'integer|exists:product_specifications,id',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'existing_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'old_price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0',
        ]);

        // dd('After validation', $request->all(), $id);
        // dd('Request data:', $request->all()); // Отладочный вывод данных запроса - УДАЛЯЕМ

        $product = Product::with('images', 'specifications')->find($id); // Загружаем отношения заранее

        // dd('After finding product', $product);

        // Удаление выбранных изображений
        if ($request->has('images_to_delete')) {
            $imagesToDelete = $request->input('images_to_delete');
            $productImages = $product->images()->whereIn('id', $imagesToDelete)->get();

            foreach ($productImages as $image) {
                // Удаляем файл из хранилища
                if (Storage::disk('public')->exists('resources/media/images/' . $image->image_path)) {
                     // Удаляем файл из хранилища, используя правильный путь
                    Storage::disk('public')->delete('resources/media/images/' . $image->image_path);
                }
                // Удаляем запись из базы данных
                $image->delete();
                // console.log('Deleted image with ID:', $image->id);
            }
            // dd('After deleting images. Remaining images:', $product->images->fresh()); // Отладочный вывод
        }

        // Загрузка новых изображений
        if ($request->hasFile('new_images')) {
            $targetDirectory = public_path('media/images/');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }

            foreach ($request->file('new_images') as $file) {
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $filePath = $targetDirectory . $filename;
                File::put($filePath, File::get($file));
                $product->images()->create([
                    'image_path' => $filename,
                    'order' => 0,
                ]);
            }
        }

        // Обработка изменения существующих изображений
        if ($request->hasFile('existing_images')) {
            $targetDirectory = public_path('media/images/');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }

            foreach ($request->file('existing_images') as $imageId => $file) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    $oldFilePath = $targetDirectory . $image->image_path;
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }

                    $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $newFilePath = $targetDirectory . $filename;
                    File::put($newFilePath, File::get($file));
                    $image->image_path = $filename;
                    $image->save();
                }
            }
        }

        // Обновляем основные данные продукта
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->qty = $request->input('qty');
        $product->color = $request->input('color');
        $product->country = $request->input('country');
        $product->category_id = $request->input('category_id'); // Убедимся, что category_id обновляется
        
        // Обработка чекбоксов статусов: если чекбокс не отправлен, устанавливаем значение в false (0)
        $product->is_bestseller = $request->has('is_bestseller');
        $product->is_new = $request->has('is_new');

        // Сохраняем старую цену. Если поле пустое или 0, устанавливаем в null или 0 (в зависимости от структуры БД)
        // Установим в null, если 0 или пусто, чтобы аксессор on_sale работал корректно
        $product->old_price = $request->input('old_price', null);

        // Поле discount_percent больше не сохраняем напрямую, оно вычисляется аксессором
        // $product->discount_percent = $request->input('discount', 0) > 0 ? $request->input('discount') : 0;

        // Добавляем сохранение рейтинга
        $product->rating = $request->input('rating', $product->rating);

        // dd($product->country);
        // dd('Before product save', $product);
        $product->save(); // Сохраняем основные данные продукта после обработки изображений
        // dd('After product save', $product);
        
        // Обработка спецификаций
        if ($request->has('specifications')) {
            $existingSpecIds = $product->specifications->pluck('id')->toArray();
            $updatedSpecIds = [];

            foreach ($request->input('specifications') as $specData) {
                if (isset($specData['id']) && $specData['id']) {
                    // Обновляем существующую спецификацию
                    $specification = $product->specifications()->where('id', $specData['id'])->first();
                    if ($specification) {
                         $specification->update([
                            'spec_name' => $specData['spec_name'],
                            'spec_value' => $specData['spec_value'],
                            'group' => $specData['group'] ?? 'Основные',
                            'is_filterable' => isset($specData['is_filterable']) ? true : false,
                            'spec_key' => $specData['spec_name'] === 'Бренд' ? 'brand' : Str::slug($specData['spec_name']),
                        ]);
                         $updatedSpecIds[] = $specData['id'];
                         // console.log('Updated specification with ID:', $specData['id']);
                    }
                } else {
                    // Создаем новую спецификацию
                     $newSpec = $product->specifications()->create([
                        'spec_key' => $specData['spec_name'] === 'Бренд' ? 'brand' : Str::slug($specData['spec_name']),
                        'spec_name' => $specData['spec_name'],
                        'spec_value' => $specData['spec_value'],
                        'group' => $specData['group'] ?? 'Основные',
                        'is_filterable' => isset($specData['is_filterable']) ? true : false,
                    ]);
                    // dd('Attempted to create new specification:', $newSpec); // Отладочный вывод после создания новой спецификации
                    // console.log('Created new specification with ID:', $newSpec->id);
                }
            }
             // dd('After specifications loop. Existing IDs:', $existingSpecIds, 'Updated IDs:', $updatedSpecIds); // Проверяем результаты цикла

             // Удаляем спецификации, которых нет в обновленном списке (если они не помечены для удаления явно)
             $deletedSpecIds = array_diff($existingSpecIds, $updatedSpecIds);
             if (!empty($deletedSpecIds)){
                  ProductSpecification::whereIn('id', $deletedSpecIds)->delete();
                   // console.log('Deleted specifications not in updated list:', $deletedSpecIds);
             }
        }

         // Удаляем спецификации, помеченные для удаления
         if ($request->has('specifications_to_delete')) {
            ProductSpecification::whereIn('id', $request->input('specifications_to_delete'))->delete();
             // console.log('Deleted specifications marked for deletion:', $request->input('specifications_to_delete'));
         }

        // dd('Before redirect');
        return redirect()->route('admin.products');
    }

    public function createProductView()
    {
        // Используем Eloquent модель Category для загрузки категорий
        $categories = \App\Models\Category::all();
        return view('admin.product-create', ['categories' => $categories]);
    }

    public function createProduct(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'country' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',
            'specifications.*.spec_name' => 'required_with:specifications|string|max:255',
            'specifications.*.spec_value' => 'required_with:specifications|string|max:255',
            'specifications.*.group' => 'nullable|string|max:255',
            'specifications.*.is_filterable' => 'nullable|boolean',
            'old_price' => 'nullable|numeric|min:0',
            'is_bestseller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'rating' => 'nullable|numeric|min:0',
        ]);

        $product = Product::create([
            'title' => $request->input('title'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'country' => $request->input('country'),
            'color' => $request->input('color'),
            'description' => $request->input('description'),
            'old_price' => $request->input('old_price', null),
            'is_bestseller' => $request->has('is_bestseller'),
            'is_new' => $request->has('is_new'),
            'rating' => $request->input('rating', 0),
        ]);

        if ($request->hasFile('new_images')) {
            $targetDirectory = public_path('media/images/');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }

            foreach ($request->file('new_images') as $file) {
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $filePath = $targetDirectory . $filename;
                File::put($filePath, File::get($file));
                $product->images()->create([
                    'image_path' => $filename,
                    'order' => 0,
                ]);
            }
        }

        if ($request->has('specifications')) {
            foreach ($request->input('specifications') as $spec) {
                if (empty($spec['spec_name']) || empty($spec['spec_value'])) {
                    continue;
                }
                $product->specifications()->create([
                    'spec_key' => $spec['spec_name'] === 'Бренд' ? 'brand' : Str::slug($spec['spec_name']),
                    'spec_name' => $spec['spec_name'],
                    'spec_value' => $spec['spec_value'],
                    'group' => $spec['group'] ?? 'Основные',
                    'is_filterable' => isset($spec['is_filterable']) ? true : false,
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Товар успешно добавлен!');
    }

        public function deleteProduct($id)
        {
            DB::table('products')->where('id', $id)->delete();

            return redirect()->route('admin.products')->with('success', 'Товар успешно удален');
        }

    public function show(Product $product)
    {
        $product->load(['category', 'specifications', 'images']);

        // Получаем все спецификации товара
        $allSpecs = $product->specifications;

        // Группируем спецификации по группам
        $groupedSpecs = $allSpecs->groupBy('group');

        // dd($groupedSpecs); // Отладочный вывод сгруппированных спецификаций

        // Рекомендации: другие товары из этой категории, кроме текущего
        $recommendedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            // Добавляем загрузку отношения 'images' для рекомендованных товаров
            ->with('images')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Добавляем логирование для проверки загрузки изображений у рекомендованных товаров
        \Log::info('Recommended products data (with images):');
        foreach ($recommendedProducts as $recProduct) {
            \Log::info('  Product ID: ' . $recProduct->id . ', Title: ' . $recProduct->title . ', Images count: ' . $recProduct->images->count());
        }

        \Log::info('Recommended products count: ' . $recommendedProducts->count() . ' for product ' . $product->id);

        return view('product', compact('product', 'groupedSpecs', 'recommendedProducts'));
    }

    // New method to serve images
    public function serveImage($filename)
    {
        // Добавляем логирование при вызове метода
        \Log::info('Attempting to serve image: ' . $filename);

        $path = resource_path('media/images/' . $filename);

        // Добавляем логирование о существовании файла
        if (!File::exists($path)) {
            \Log::warning('Image file not found: ' . $path);
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        // Добавляем логирование об успешной отдаче файла
        \Log::info('Successfully served image: ' . $filename);

        return $response;
    }
}
