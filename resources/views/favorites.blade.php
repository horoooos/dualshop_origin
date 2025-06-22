@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="about__title mb-4">Избранные товары</h1>
        @if(!$favorites->isEmpty())
        <span class="badge bg-secondary">{{ $favorites->count() }} {{ trans_choice('товар|товара|товаров', $favorites->count()) }}</span>
        @endif
    </div>

    @if($favorites->isEmpty())
        <div class="empty-cart-message text-center py-5">
            <i class="bi bi-heart display-4 text-muted mb-3" style="font-size: 4rem;"></i>
            <h2 class="mb-3">У вас пока нет избранных товаров</h2>
            <p class="text-muted mb-4">Добавляйте понравившиеся товары, чтобы вернуться к ним позже</p>
            <a href="{{ route('catalog.categories') }}" class="btn btn-primary btn-lg">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($favorites as $product)
                <div class="col favorite-item" data-product-id="{{ $product->id }}">
                    <div class="top-categories-card">
                        <div class="position-relative">
                            <form action="{{ route('favorites.toggle', $product) }}" method="POST" class="remove-favorite-form">
                                @csrf
                                <button type="submit" 
                                        class="btn position-absolute favorite-toggle" 
                                        style="top: 10px; right: 10px; z-index: 10;" 
                                        title="Убрать из избранного">
                                    <i class="bi bi-heart-fill text-danger"></i>
                                </button>
                            </form>
                            
                            <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                                <div class="text-center mb-3">
                                    @if($product->img)
                                        <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                            class="top-categories-image" 
                                            alt="{{ $product->title }}">
                                    @else
                                        <div class="placeholder-image d-flex align-items-center justify-content-center bg-light" style="height: 160px;">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none">
                                <h5 class="top-categories-product-title">{{ $product->title }}</h5>
                            </a>
                            
                            @if($product->rating)
                            <div class="top-categories-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                                <small class="text-muted ms-2">{{ number_format($product->rating, 1) }}</small>
                            </div>
                            @endif
                            
                            <div class="top-categories-price mb-3">
                                @if(isset($product->old_price) && $product->old_price > $product->price)
                                    <del class="text-muted me-2">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</del>
                                @endif
                                {{ number_format($product->price, 0, ',', ' ') }} ₽
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('product.show', $product) }}" class="top-categories-btn flex-grow-1">
                                    Подробнее <i class="bi bi-arrow-right"></i>
                                </a>
                                @if($product->in_stock)
                                <button onclick="addToCart({{ $product->id }})" class="btn-cart-small">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Toasts -->
<div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div id="cartToast" class="toast success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товар добавлен в корзину</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="favoriteToast" class="toast success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товар удален из избранного</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
.favorite-toggle {
    background: none;
    border: none;
    padding: 0.5rem;
    opacity: 0.9;
    transition: all 0.2s ease;
}

.favorite-toggle:hover {
    transform: scale(1.1);
    opacity: 1;
}

.favorite-toggle i {
    filter: drop-shadow(0 0 2px white);
    font-size: 1.2rem;
}

.btn-cart-small {
    background: #000;
    color: white;
    border: none;
    border-radius: 5px;
    width: 46px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-cart-small:hover {
    background: #333;
}

/* Стили для сообщения о пустой корзине (копируем из cart.blade.php, переиспользуем для избранного) */
.empty-cart-message {
    border: 2px dashed #ccc; /* Пунктирная рамка */
    border-radius: 15px; /* Скругленные углы */
    padding: 40px; /* Внутренние отступы */
    background-color: #f8f9fa; /* Светлый фон */
    margin-top: 30px; /* Отступ сверху */
}

.empty-cart-message .bi-heart { /* Используем bi-heart для избранного */
    font-size: 4rem; /* Увеличим размер иконки */
    color: #dc3545 !important; /* Красный цвет для сердца */
}

.empty-cart-message h2 {
    font-size: 1.8rem; /* Размер заголовка */
    font-weight: 600;
    color: #343a40;
}

.empty-cart-message p {
    font-size: 1.1rem; /* Размер текста */
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка удаления из избранного с AJAX
    document.querySelectorAll('.remove-favorite-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productItem = this.closest('.favorite-item');
            const productId = productItem.dataset.productId;
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Анимация удаления
                    productItem.style.opacity = 0;
                    productItem.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        productItem.remove();
                        
                        // Если все товары удалены - показать сообщение
                        if (document.querySelectorAll('.favorite-item').length === 0) {
                            location.reload();
                        }
                    }, 300);
                    
                    const toast = new bootstrap.Toast(document.getElementById('favoriteToast'));
                    toast.show();
                }
            });
        });
    });
});

function addToCart(productId) {
    if(!productId) return;
    
    fetch(`/add-to-cart/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            const toast = new bootstrap.Toast(document.getElementById('cartToast'));
            toast.show();
        }
    });
}
</script>
@endsection 