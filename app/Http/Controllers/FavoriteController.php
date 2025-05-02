<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites;
        return view('favorites', compact('favorites'));
    }

    public function toggle(Product $product)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                Log::error('User not authenticated when toggling favorite');
                return response()->json(['error' => 'Пользователь не авторизован'], 401);
            }

            $exists = $user->favorites()->where('product_id', $product->id)->exists();
            
            Log::info('Attempting to toggle favorite', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'exists' => $exists
            ]);

            if ($exists) {
                $user->favorites()->detach($product->id);
                Log::info('Successfully removed from favorites', [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]);
                
                if (request()->ajax()) {
                    return response()->json([
                        'status' => 'removed',
                        'message' => 'Товар удален из избранного'
                    ]);
                }
                
                return redirect()->back();
            }

            $user->favorites()->attach($product->id);
            Log::info('Successfully added to favorites', [
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'added',
                    'message' => 'Товар добавлен в избранное'
                ]);
            }
            
            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('Error toggling favorite', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Произошла ошибка при обновлении избранного: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Произошла ошибка при обновлении избранного');
        }
    }
} 