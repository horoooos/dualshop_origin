<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    // Изменение количества товара в корзине
    public function changeQty(Request $request, CartItem $cartItem)
    {
        $action = $request->input('action');
        
        if ($action === 'increase') {
            if ($cartItem->quantity < $cartItem->product->qty) {
                $cartItem->increment('quantity');
            }
        } elseif ($action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }

        return redirect()->back();
    }

    // Отображение корзины
    public function index()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        return view('cart', compact('items'));
    }

    // Добавление товара в корзину
    public function addToCart(Product $product)
    {
        $user = auth()->user();
        
        if ($product->qty <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Товара нет в наличии'
            ]);
        }

        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            if ($cartItem->quantity < $product->qty) {
                $cartItem->increment('quantity');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Количество товара в корзине увеличено'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Достигнут лимит доступного количества товара'
                ]);
            }
        } else {
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => 1
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Товар успешно добавлен в корзину'
            ]);
        }
    }

    // Удаление товара из корзины
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав на удаление этого товара');
        }

        $cartItem->delete();
        return redirect()->back()->with('success', 'Товар успешно удален из корзины');
    }
}
