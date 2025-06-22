<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Маршруты для категорий
Route::prefix('categories')->group(function () {
    Route::get('/', [App\Http\Controllers\CategoryController::class, 'getAllCategories']);
    Route::get('/type/{type}', [App\Http\Controllers\CategoryController::class, 'getCategoriesByType']);
    Route::get('/{id}/product-count', [App\Http\Controllers\CategoryController::class, 'getCategoryProductCount']);
});
