@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container py-4">
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif
                </div>
                <div class="profile-details">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <div class="profile-contacts">
                        @if($user->phone)
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>
                                {{ $user->phone }}
                            </div>
                        @endif
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            {{ $user->email }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
                    Редактировать профиль
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Выйти
                    </button>
                </form>
            </div>
        </div>

        <div class="profile-content">
            <h2 class="orders-title">Мои заказы</h2>
            
            @if(count($orders) > 0)
                <div class="orders-list">
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-number">
                                        Заказ №{{ $order->number }}
                                    </div>
                                    <div class="order-date">
                                        {{ $order->date->format('d.m.Y') }}
                                    </div>
                                </div>
                                <div class="order-status {{ strtolower($order->status) }}">
                                    {{ $order->status }}
                                </div>
                            </div>

                            <div class="order-products">
                                @foreach($order->products as $product)
                                    <div class="order-product-item">
                                        <div class="product-details">
                                            <span class="product-name">{{ $product->title }}</span>
                                            <span class="product-quantity">× {{ $product->qty }}</span>
                                        </div>
                                        <div class="product-price">
                                            {{ number_format($product->price * $product->qty, 0, ',', ' ') }} ₽
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-footer">
                                <div class="order-total">
                                    <span class="total-label">Итого:</span>
                                    <span class="total-amount">{{ number_format($order->totalPrice, 0, ',', ' ') }} ₽</span>
                                </div>
                                @if($order->status == 'Новый')
                                    <form action="/order-delete/{{ $order->number }}" method="post" class="delete-order-form">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="delete-order-btn">
                                            Отменить заказ
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-orders">
                    <i class="bi bi-bag"></i>
                    <p>У вас пока нет заказов</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.profile-container {
    background: #f9f9f9;
    min-height: 100vh;
    padding: 20px 0;
}

.profile-header {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-avatar i {
    font-size: 40px;
    color: #666;
}

.profile-name {
    font-family: 'Roboto', sans-serif;
    font-size: 24px;
    font-weight: 500;
    margin: 0 0 10px 0;
}

.profile-contacts {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 14px;
}

.contact-item i {
    font-size: 16px;
}

.profile-actions {
    display: flex;
    gap: 15px;
}

.edit-profile-btn, .logout-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.edit-profile-btn {
    background: #f0f0f0;
    color: #000;
    border: none;
    text-decoration: none;
}

.edit-profile-btn:hover {
    background: #e0e0e0;
}

.logout-btn {
    background: none;
    border: 1px solid #dc3545;
    color: #dc3545;
}

.logout-btn:hover {
    background: #dc3545;
    color: white;
}

.profile-content {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
}

.orders-title {
    font-family: 'Roboto', sans-serif;
    font-size: 20px;
    font-weight: 500;
    margin: 0 0 20px 0;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f9f9f9;
    border-bottom: 1px solid #eee;
}

.order-number {
    font-weight: 500;
    margin-bottom: 5px;
}

.order-date {
    font-size: 14px;
    color: #666;
}

.order-status {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
}

.order-status.новый {
    background: #e3f2fd;
    color: #1976d2;
}

.order-status.подтвержден {
    background: #e8f5e9;
    color: #2e7d32;
}

.order-status.отменен {
    background: #ffebee;
    color: #c62828;
}

.order-products {
    padding: 20px;
}

.order-product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.order-product-item:last-child {
    border-bottom: none;
}

.product-details {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-name {
    font-weight: 500;
}

.product-quantity {
    color: #666;
}

.product-price {
    font-weight: 500;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f9f9f9;
    border-top: 1px solid #eee;
}

.order-total {
    display: flex;
    align-items: center;
    gap: 10px;
}

.total-label {
    font-weight: 500;
}

.total-amount {
    font-size: 18px;
    font-weight: 600;
}

.delete-order-btn {
    padding: 8px 16px;
    border: 1px solid #dc3545;
    border-radius: 6px;
    background: none;
    color: #dc3545;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.delete-order-btn:hover {
    background: #dc3545;
    color: white;
}

.no-orders {
    text-align: center;
    padding: 40px 0;
    color: #666;
}

.no-orders i {
    font-size: 48px;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }

    .profile-info {
        flex-direction: column;
    }

    .profile-contacts {
        align-items: center;
    }

    .order-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .order-product-item {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .order-footer {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .order-total {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

@endsection