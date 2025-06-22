@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="about__title mb-4">Корзина</h1>
    
    @if($items->isEmpty())
        <div class="empty-cart-message text-center py-5">
            <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
            <h2 class="mb-3">Ваша корзина пуста</h2>
            <p class="text-muted mb-4">Добавьте товары в корзину, чтобы оформить заказ.</p>
            <a href="{{ route('catalog.categories') }}" class="btn btn-primary btn-lg">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="cart-container">
            <div class="cart-items">
                @foreach($items as $item)
                    <div class="cart-item" data-item-id="{{ $item->id }}">
                        <div class="cart-item-image">
                            @php
                                $cartImageFileName = null;
                                if (isset($item->product->images) && count($item->product->images) > 0) {
                                    $cartImageFileName = $item->product->images->first()->image_path;
                                } elseif (!empty($item->product->img)) {
                                    $cartImageFileName = $item->product->img;
                                }
                            @endphp
                            @if($cartImageFileName)
                                <img src="{{ asset('media/images/' . $cartImageFileName) }}" alt="{{ $item->product->title }}">
                            @else
                                <div style="width: 100px; height: 100px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                                    Нет фото
                                </div>
                            @endif
                        </div>
                        <div class="cart-item-info">
                            <h3 class="cart-item-title">{{ $item->product->title }}</h3>
                            <div class="cart-item-price">
                                @if($item->product->discount > 0)
                                    <span class="original-price">{{ number_format($item->product->price, 0, ',', ' ') }} ₽</span>
                                    <span class="discount-price">{{ number_format($item->product->price * (1 - $item->product->discount / 100), 0, ',', ' ') }} ₽</span>
                                @else
                                    <span class="current-price">{{ number_format($item->product->price, 0, ',', ' ') }} ₽</span>
                                @endif
                            </div>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn" 
                                        onclick="changeQuantity({{ $item->id }}, 'decrease')"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span class="quantity-value">{{ $item->quantity }}</span>
                                <button class="quantity-btn" 
                                        onclick="changeQuantity({{ $item->id }}, 'increase')"
                                        {{ $item->quantity >= $item->product->qty ? 'disabled' : '' }}>
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="cart-item-total">
                            <div class="total-price">
                                @if($item->product->discount > 0)
                                    {{ number_format($item->product->price * (1 - $item->product->discount / 100) * $item->quantity, 0, ',', ' ') }} ₽
                                @else
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                                @endif
                            </div>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="remove-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Итого:</span>
                    <span class="summary-total">
                        {{ number_format($items->sum(function($item) {
                            return $item->product->discount > 0 
                                ? $item->product->price * (1 - $item->product->discount / 100) * $item->quantity
                                : $item->product->price * $item->quantity;
                        }), 0, ',', ' ') }} ₽
                    </span>
                </div>
                <a href="{{ route('create-order') }}" class="checkout-btn">
                    Оформить заказ
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    @endif
</div>

@auth
<script>
function changeQuantity(cartItemId, action) {
    const button = event.target.closest('button');
    button.disabled = true;
    
    fetch(`/cart/${cartItemId}/change-quantity`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action })
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        }
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endauth

<style>
.cart-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.cart-item {
    display: grid;
    grid-template-columns: 150px 1fr auto;
    gap: 20px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 12px;
    align-items: center;
}

.cart-item-image {
    width: 150px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.cart-item-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cart-item-title {
    font-family: 'Roboto', sans-serif;
    font-size: 18px;
    font-weight: 500;
    color: #000;
    margin: 0;
}

.cart-item-price {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
}

.discount-price {
    color: #dc3545;
    font-weight: 600;
    font-size: 18px;
}

.current-price {
    font-weight: 600;
    font-size: 18px;
    color: #000;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
}

.quantity-btn {
    background: none;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quantity-btn:not(:disabled):hover {
    background: #000;
    color: white;
    border-color: #000;
}

.quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity-value {
    font-size: 16px;
    min-width: 30px;
    text-align: center;
}

.cart-item-total {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 15px;
}

.total-price {
    font-weight: 600;
    font-size: 20px;
    color: #000;
}

.remove-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 5px;
    transition: all 0.3s ease;
}

.remove-btn:hover {
    color: #bb2d3b;
}

.cart-summary {
    background: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
}

.summary-total {
    color: #000;
}

.checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #000;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 15px 30px;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 100%;
}

.checkout-btn:hover {
    background: #333;
    color: white;
}

@media (max-width: 768px) {
    .cart-item {
        grid-template-columns: 100px 1fr;
        grid-template-rows: auto auto;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
    }

    .cart-item-total {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }
}

@media (max-width: 576px) {
    .cart-container {
        padding: 15px;
    }

    .cart-item {
        padding: 15px;
    }

    .cart-item-title {
        font-size: 16px;
    }

    .total-price {
        font-size: 18px;
    }

    .summary-row {
        font-size: 18px;
    }
}

/* Стили для сообщения о пустой корзине */
.empty-cart-message {
    border: 2px dashed #ccc;
    border-radius: 15px;
    padding: 40px;
    background-color: #f8f9fa;
    margin-top: 30px;
}

.empty-cart-message .bi-cart-x {
    font-size: 4rem;
    color: #adb5bd !important;
}

.empty-cart-message h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #343a40;
}

.empty-cart-message p {
    font-size: 1.1rem;
    color: #6c757d;
}
</style>
@endsection
