<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Order;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $goodOrders = [];
        $orders = Order::with(['product'])
            ->where('user_id', $request->user()->id)
            ->get()
            ->groupBy('number');

        foreach ($orders as $orderGroup) {
            $firstOrder = $orderGroup->first();
            $totalPrice = 0;
            $totalQty = 0;
            $products = [];

            foreach ($orderGroup as $orderItem) {
                $totalPrice += $orderItem->product->price * $orderItem->quantity;
                $totalQty += $orderItem->quantity;

                $products[] = (object)[
                    'title' => $orderItem->product->title,
                    'price' => $orderItem->product->price,
                    'qty' => $orderItem->quantity,
                ];
            }

            $goodOrders[] = (object)[
                'date' => $firstOrder->created_at,
                'number' => $firstOrder->number,
                'status' => $firstOrder->status,
                'products' => $products,
                'totalPrice' => $totalPrice,
                'totalQty' => $totalQty,
            ];
        }

        return view('profile.index', [
            'user' => $request->user(),
            'orders' => $goodOrders
        ]);
    }

    public function edit(Request $request): View
    {
        $goodOrders = [];
        $orders = Order::with(['product'])
            ->where('user_id', $request->user()->id)
            ->get()
            ->groupBy('number');

        foreach ($orders as $orderGroup) {
            $firstOrder = $orderGroup->first();
            $totalPrice = 0;
            $totalQty = 0;
            $products = [];

            foreach ($orderGroup as $orderItem) {
                $totalPrice += $orderItem->product->price * $orderItem->quantity;
                $totalQty += $orderItem->quantity;

                $products[] = [
                    'title' => $orderItem->product->title,
                    'price' => $orderItem->product->price,
                    'qty' => $orderItem->quantity,
                ];
            }

            $goodOrders[] = [
                'date' => $firstOrder->created_at,
                'number' => $firstOrder->number,
                'status' => $firstOrder->status,
                'products' => $products,
                'totalPrice' => $totalPrice,
                'totalQty' => $totalQty,
            ];
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'orders' => $goodOrders
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatars'), $filename);
            $data['avatar'] = 'avatars/' . $filename;
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Выход пользователя из системы
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
