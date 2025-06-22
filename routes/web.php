<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для вашего приложения.
| Эти маршруты загружаются через RouteServiceProvider, и все они
| будут принадлежать группе посредников "web".
|
*/

// Главная страница
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Маршруты каталога
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/categories', [CatalogController::class, 'categories'])->name('catalog.categories');
Route::get('/catalog/product/{product}', [ProductController::class, 'show'])->name('catalog.product');
Route::get('/catalog/search', [CatalogController::class, 'search'])->name('catalog.search');
Route::get('/catalog/filter', [CatalogController::class, 'index'])->name('catalog.filter');
Route::get('/catalog/{category}', [CatalogController::class, 'index'])->where('category', '[0-9]+')->name('catalog.category');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Route to serve product images from resources/media/images
Route::get('/get-product-image/{filename}', [ProductController::class, 'serveImage'])->name('product.image');

// Статические страницы
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/shops', [NewsController::class, 'index'])->name('shops');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/where', function () { return view('where'); })->name('where');
Route::get('/delivery', function () { return view('delivery'); })->name('delivery');
Route::get('/stores', function () { return view('stores'); })->name('stores');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Возвращаем старый маршрут для promotions:
Route::get('/promotions', function () { return view('promotions'); })->name('promotions');

// Маршруты для администратора
Route::prefix('/admin')->middleware(['auth', 'is_admin'])->group(function () {
    // Главная страница админки
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

    // Маршруты для продуктов
    Route::get('/products', [ProductController::class, 'getProducts'])->name('admin.products');
    Route::get('/product-create', [ProductController::class, 'createProductView'])->name('admin.products.create');
    Route::post('/product-create', [ProductController::class, 'createProduct'])->name('admin.products.store');
    Route::get('/product-edit/{id}', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::patch('/product-update/{id}', [ProductController::class, 'editProduct'])->name('admin.products.update');
    Route::delete('/product-delete/{id}', [ProductController::class, 'deleteProduct'])->name('admin.products.delete');

    // Маршруты для категорий
    Route::get('/categories', [CategoriesController::class, 'getCategories'])->name('admin.categories');
    Route::get('/category-create', [CategoriesController::class, 'createCategoryView'])->name('admin.categories.create');
    Route::post('/category-create', [CategoriesController::class, 'createCategory'])->name('admin.categories.store');
    Route::get('/category-edit/{id}', [CategoriesController::class, 'editCategoryById'])->name('admin.categories.edit');
    Route::patch('/category-update/{id}', [CategoriesController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/category-delete/{id}', [CategoriesController::class, 'deleteCategory'])->name('admin.categories.delete');

    // Маршруты для новостей
    Route::get('/news', [NewsController::class, 'adminIndex'])->name('admin.news');
    Route::get('/news-create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::post('/news-create', [NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/news-edit/{id}', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::patch('/news-update/{id}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news-delete/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');
    
    // Маршруты для заказов
    Route::get('/orders', [OrderController::class, 'getOrders'])->name('admin.orders');
    Route::patch('/order-status/{action}/{number}', [OrderController::class, 'editOrderStatus'])->name('admin.orders.updateStatus');
    Route::delete('/orders/{number}', [OrderController::class, 'deleteOrderAdmin'])->name('admin.orders.delete');
});

// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Избранные товары
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/{product}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/add-to-cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/{cartItem}/change-quantity', [CartController::class, 'changeQty'])->name('cart.change-quantity');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Заказы
    Route::get('/create-order', [OrderController::class, 'index'])->name('create-order');
    Route::post('/create-order', [OrderController::class, 'createOrder']);
    Route::delete('/order-delete/{number}', [OrderController::class, 'deleteOrder'])->name('order.delete');
});

// Аутентификация
require __DIR__.'/auth.php';

Route::get('/debug-categories', function() {
    $categories = \App\Models\Category::all();
    return response()->json([
        'count' => $categories->count(),
        'categories' => $categories->toArray()
    ]);
});

Route::get('/media/images/{filename}', function ($filename) {
    $path = resource_path('media/images/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('filename', '.*');
