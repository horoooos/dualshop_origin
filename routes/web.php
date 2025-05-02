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

// Каталог и продукты
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{category}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Статические страницы
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/shops', function () { return view('shops'); })->name('shops');
Route::get('/where', function () { return view('where'); })->name('where');
Route::get('/delivery', function () { return view('delivery'); })->name('delivery');
Route::get('/stores', function () { return view('stores'); })->name('stores');
Route::get('/promotions', function () { return view('promotions'); })->name('promotions');

// Маршруты для администратора
Route::middleware(['auth', 'is-admin'])->group(function () {
    // Маршруты для продуктов
    Route::get('/products', [ProductController::class, 'getProducts'])->name('admin.products');
    Route::get('/product-create', [ProductController::class, 'createProductView']);
    Route::post('/product-create', [ProductController::class, 'createProduct']);
    Route::get('/product-edit/{id}', [ProductController::class, 'getProductById']);
    Route::patch('/product-update/{id}', [ProductController::class, 'editProduct']);
    Route::delete('/product-delete/{id}', [ProductController::class, 'deleteProduct']);

    // Маршруты для категорий
    Route::get('/categories', [CategoriesController::class, 'getCategories'])->name('admin.categories');
    Route::get('/category-create', [CategoriesController::class, 'createCategoryView']);
    Route::post('/category-create', [CategoriesController::class, 'createCategory']);
    Route::get('/category-edit/{id}', [CategoriesController::class, 'editCategoryById']);
    Route::patch('/category-update/{id}', [CategoriesController::class, 'updateCategory']);
    Route::delete('/category-delete/{id}', [CategoriesController::class, 'deleteCategory']);

    // Маршруты для заказов
    Route::get('/orders', [OrderController::class, 'getOrders'])->name('admin.orders');
    Route::get('/order-status/{action}/{number}', [OrderController::class, 'editOrderStatus']);
    Route::patch('/order-status/{action}/{number}', [OrderController::class, 'editOrderStatus']);
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
