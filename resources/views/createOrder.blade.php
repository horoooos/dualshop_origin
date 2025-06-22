@extends('layouts.app')
@section('content')

<div class="container py-4">
    <h1 class="about__title mb-4">Оформление заказа</h1>
    
    <div class="order-container">
        <div class="order-items">
            @foreach($cart as $item)
                <div class="order-item">
                    <div class="order-item-info">
                        <h3 class="order-item-title">{{ $item->product->title }}</h3>
                        <div class="order-item-details">
                            <div class="order-item-quantity">
                                Количество: <span>{{ $item->quantity }}</span>
                            </div>
                            <div class="order-item-price">
                                @if($item->product->discount > 0)
                                    <span class="original-price">{{ number_format($item->product->price, 0, ',', ' ') }} ₽</span>
                                    <span class="discount-price">{{ number_format($item->product->price * (1 - $item->product->discount / 100), 0, ',', ' ') }} ₽</span>
                                @else
                                    <span class="current-price">{{ number_format($item->product->price, 0, ',', ' ') }} ₽</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="order-item-total">
                        <div class="total-price">
                            @if($item->product->discount > 0)
                                {{ number_format($item->product->price * (1 - $item->product->discount / 100) * $item->quantity, 0, ',', ' ') }} ₽
                            @else
                                {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="order-summary">
            <div class="summary-row">
                <span>Итого к оплате:</span>
                <span class="summary-total">{{ number_format($total, 0, ',', ' ') }} ₽</span>
            </div>
            
            <form class="order-form" id="orderForm">
                @csrf
                <div class="form-group">
                    <input type="password" 
                           id="password" 
                           class="form-input" 
                           name="password" 
                           placeholder="Введите пароль для подтверждения" 
                           required>
                </div>
                <button type="button" id="submitBtn" class="checkout-btn">
                    Подтвердить заказ
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#submitBtn').click(function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.post('/create-order', {
            password: $('#password').val(),
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Заказ оформлен!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Перейти в профиль',
                    background: '#fff',
                    confirmButtonColor: '#000'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('profile.index') }}';
                    }
                });
            }
        })
        .fail(function(xhr) {
            let message = 'Произошла ошибка при создании заказа';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                title: 'Ошибка',
                text: message,
                icon: 'error',
                confirmButtonText: 'Понятно',
                background: '#fff',
                confirmButtonColor: '#000'
            });
        })
        .always(function() {
            button.prop('disabled', false);
        });
    });
});
</script>

<style>
.order-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 12px;
}

.order-item-info {
    flex: 1;
}

.order-item-title {
    font-family: 'Roboto', sans-serif;
    font-size: 18px;
    font-weight: 500;
    color: #000;
    margin: 0 0 10px 0;
}

.order-item-details {
    display: flex;
    gap: 20px;
    color: #666;
}

.order-item-quantity span {
    font-weight: 600;
    color: #000;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    display: block;
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

.order-item-total {
    font-weight: 600;
    font-size: 20px;
    color: #000;
}

.order-summary {
    background: #f9f9f9;
    border-radius: 12px;
    padding: 20px;
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

.order-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-input:focus {
    border-color: #000;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
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
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.checkout-btn:hover {
    background: #333;
}

.checkout-btn:disabled {
    background: #666;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .order-item-details {
        flex-direction: column;
        gap: 10px;
    }

    .order-item-total {
        align-self: flex-end;
    }
}

@media (max-width: 576px) {
    .order-container {
        padding: 15px;
    }

    .order-item {
        padding: 15px;
    }

    .order-item-title {
        font-size: 16px;
    }

    .summary-row {
        font-size: 18px;
    }
}
</style>

@endsection
