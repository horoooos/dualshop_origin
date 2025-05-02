<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->quantity * $item->product->price;
        }

        return view('createOrder', [
            'cart' => $cartItems,
            'total' => $total
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->get('password'), $request->user()->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверный пароль. Пожалуйста, проверьте правильность введенного пароля.'
            ], 403);
        }

        $cartItems = $request->user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Корзина пуста'
            ], 400);
        }

        $orderNumber = $this->generateOrderNumber();

        DB::transaction(function () use ($cartItems, $orderNumber, $request) {
            foreach ($cartItems as $item) {
                Order::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'number' => $orderNumber,
                    'status' => 'Новый',
                ]);

                // Уменьшаем количество товара на складе
                $item->product->decrement('qty', $item->quantity);
            }

            // Очищаем корзину
            $request->user()->cartItems()->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Заказ успешно сформирован! Номер вашего заказа: ' . $orderNumber,
            'orderNumber' => $orderNumber
        ]);
    }

    private function generateOrderNumber()
    {
        do {
            $orderNumber = Str::random(8);
        } while (Order::where('number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function getOrders(Request $request)
    {
        $filter = $request->query('filter');
        $query = Order::with(['user', 'product']);

        if ($filter === 'new') {
            $query->where('status', 'Новый');
        } elseif ($filter === 'confirmed') {
            $query->where('status', 'Подтвержден');
        } elseif ($filter === 'canceled') {
            $query->where('status', 'Отменен');
        }

        $orders = $query->get()->groupBy('number');
        $goodOrders = [];

        foreach ($orders as $orderGroup) {
            $firstOrder = $orderGroup->first();
            $user = $firstOrder->user;
            $totalPrice = 0;
            $totalQty = 0;
            $products = [];

            foreach ($orderGroup as $orderItem) {
                $product = $orderItem->product;
                $totalPrice += $product->price * $orderItem->quantity;
                $totalQty += $orderItem->quantity;

                $products[] = (object)[
                    'title' => $product->title,
                    'price' => $product->price,
                    'qty' => $orderItem->quantity,
                ];
            }

            $goodOrders[] = (object)[
                'name' => $user->surname . ' ' . $user->name . ' ' . $user->patronymic,
                'number' => $firstOrder->number,
                'products' => $products,
                'date' => $firstOrder->created_at,
                'totalPrice' => $totalPrice,
                'totalQty' => $totalQty,
                'status' => $firstOrder->status,
            ];
        }

        return view('admin.orders', ['orders' => $goodOrders]);
    }

    public function editOrderStatus(Request $request, $action, $number)
    {
        if (!in_array($action, ['confirm', 'cancel'])) {
            return abort(400, 'Invalid action');
        }

        $orders = Order::where('number', $number);
        
        if (!$orders->exists()) {
            return abort(404, 'Order not found');
        }

        $status = $action === 'confirm' ? 'Подтвержден' : 'Отменен';
        $orders->update(['status' => $status]);

        return redirect()->route('admin.orders')->with('success', 'Статус заказа успешно обновлен');
    }

    public function deleteOrder($number)
    {
        $orders = Order::where('number', $number);
        
        if (!$orders->exists()) {
            return abort(404, 'Заказ не найден');
        }

        $firstOrder = $orders->first();
        if ($firstOrder->status !== 'Новый') {
            return back()->with('error', 'Можно удалять только новые заказы');
        }

        // Возвращаем товары на склад
        foreach ($orders->get() as $order) {
            $order->product->increment('qty', $order->quantity);
        }

        // Удаляем заказ
        $orders->delete();

        return redirect()->route('profile.index')->with('success', 'Заказ успешно удален');
    }
}
