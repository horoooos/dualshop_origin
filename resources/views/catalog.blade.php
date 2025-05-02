@extends('layouts.app')
@section('content')

    <section class="catalog container pt-4">
        <div class="catalog_text">
            <h3 class="about__title text-start">Сортировать по</h3>
            <div class="catalog__sort">
                <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'country' ? '_desc' : '' }}=country" class="catalog__sort-item {{ (request()->query('sort_by') == 'country' ? 'active' : request()->query('sort_by_desc') == 'country') ? 'active' : '' }}">Страна поставщика</a>
                <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'title' ? '_desc' : '' }}=title" class="catalog__sort-item {{ (request()->query('sort_by') == 'title' ? 'active' : request()->query('sort_by_desc') == 'title') ? 'active' : '' }}">Название</a>
                <a href="{{ $params->has('filter') ? '?filter=' . $params['filter'] . '&' : '?' }}sort_by{{ $params->has('sort_by') == 'price' ? '_desc' : '' }}=price" class="catalog__sort-item {{ (request()->query('sort_by') == 'price' ? 'active' : request()->query('sort_by_desc') == 'price') ? 'active' : '' }}">Цена</a>
                <a href="/catalog" class="catalog__sort-item--default">Сбросить</a>
            </div>
        </div>
        <div class="catalog__filter">
            @foreach($categories as $category)
                <a href="{{ $params->has('sort_by') ? '?sort_by=' . $params['sort_by'] . '&' : '?' }} filter={{ $category->id }}" class="catalog__filter-item {{ request()->query('filter') == $category->id ? 'active' : '' }}">{{ $category->product_type }}</a>
            @endforeach
        </div>
        <div class="catalog__list">
            @if(count($products) > 0)
                @foreach($products as $product)
                    <div class="catalog__item">
                        <div class="product-image-container">
                            <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" alt="{{ $product->title }}" class="product-imagee">
                        </div>
                        
                        <div class="product-info">
                            <a href="/product/{{ $product->id }}" class="product-titlee">
                                {{ $product->title }}
                            </a>
                            <div class="product__description">
                                <h4>Описание:</h4>
                                <p>{{ $product->description ?? 'Описание отсутствует' }}</p>
                            </div>
                        </div>

                        <div class="product-price-block">
                            <div class="product-price">
                                {{ number_format($product->price, 0, ',', ' ') }} ₽
                            </div>
                            <div class="product-credit">
                                от {{ number_format($product->price / 12, 0, ',', ' ') }} ₽/мес
                            </div>
                            @auth
                            <div class="product-actions d-flex gap-2">
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-dark">
                                    <i class="bi bi-cart-plus"></i> В корзину
                                </button>
                                <button onclick="toggleFavorite({{ $product->id }})" class="btn btn-outline-danger favorite-toggle" data-product-id="{{ $product->id }}">
                                    <i class="bi bi-heart{{ auth()->user()->favorites->contains($product->id) ? '-fill' : '' }}"></i>
                                </button>
                            </div>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <h3 class="text-center">Ничего не найдено</h3>
                </div>
            @endif
        </div>
    </section>

    @auth
    <div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Успешно</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-success text-white">
                Товар добавлен в корзину
            </div>
        </div>
        
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong class="me-auto">Ошибка</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-danger text-white">
                Товара нет в наличии
            </div>
        </div>
    </div>

    <script>
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
        .then(response => response.json())
        .then(data => {
            const toast = new bootstrap.Toast(
                document.getElementById(data.status === 'success' ? 'successToast' : 'errorToast')
            );
            
            if (data.status === 'success') {
                document.querySelector('#successToast .toast-body').textContent = data.message;
                // Обновляем количество товаров в корзине в шапке
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = parseInt(cartCount.textContent || 0) + 1;
                }
            } else {
                document.querySelector('#errorToast .toast-body').textContent = data.message;
            }
            
            toast.show();
        })
        .catch(error => {
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            document.querySelector('#errorToast .toast-body').textContent = 'Произошла ошибка при добавлении товара';
            toast.show();
        });
    }

    function toggleFavorite(productId) {
        if(!productId) return;
        
        fetch(`/favorites/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const button = document.querySelector(`.favorite-toggle[data-product-id="${productId}"] i`);
            if (data.status === 'added') {
                button.classList.remove('bi-heart');
                button.classList.add('bi-heart-fill');
            } else {
                button.classList.remove('bi-heart-fill');
                button.classList.add('bi-heart');
            }
        })
        .catch(error => {
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            document.querySelector('#errorToast .toast-body').textContent = 'Произошла ошибка при обновлении избранного';
            toast.show();
        });
    }
    </script>

    <style>
    .product-actions {
        margin-top: 1rem;
    }

    .btn-dark {
        background-color: #000;
        border-color: #000;
        color: #fff;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-dark:hover {
        background-color: #333;
        border-color: #333;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }

    .favorite-toggle {
        min-width: 42px;
    }

    .toast {
        min-width: 300px;
        background: transparent;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
    }

    .toast-header {
        border-bottom: none;
        padding: 12px 15px;
        border-radius: 8px 8px 0 0;
    }

    .toast-body {
        padding: 12px 15px;
        border-radius: 0 0 8px 8px;
    }

    .btn-close {
        opacity: 0.8;
        padding: 12px;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Анимация появления */
    .toast.showing {
        opacity: 1 !important;
        transform: translateX(0);
        transition: all 0.3s ease;
    }

    .toast.hide {
        opacity: 0 !important;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }

    /* Убираем паддинги у контейнера */
    .position-fixed {
        padding: 0;
    }

/* Стили для активных состояний кнопок */
.btn-check:checked + .btn,
.btn.active,
.btn.show,
.btn:first-child:active,
:not(.btn-check) + .btn:active {
    color: white !important; /* Белый текст для контраста */
    background-color: black !important;
    border-color: black !important;
    box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.1) !important; /* Светлая тень */
}

/* Состояние hover для чёрных кнопок */
.btn-dark:hover {
    background-color: #333 !important; /* Тёмно-серый при наведении */
    border-color: #333 !important;
}

/* Адаптация для светлой темы (если нужно) */
@media (prefers-color-scheme: light) {
    .btn-check:checked + .btn,
    .btn.active {
        color: white !important;
        background-color: #212529 !important; /* Чуть светлее чёрного */
    }
}
    </style>
    @endauth
@endsection
