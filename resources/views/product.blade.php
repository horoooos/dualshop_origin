@extends('layouts.app')

@section('content')

{{-- @php dd($product); @endphp --}}
{{-- @php dd($product->toArray()); @endphp --}}
{{-- @php dd($specifications->toArray()); @endphp --}}
{{-- @php dd($groupedSpecs->toArray()); @endphp --}}
{{-- @php dd($specificationsData->toArray()); @endphp --}}

<div class="container mx-auto px-4 py-8">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.categories') }}">Каталог</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('catalog.category', $product->category->id) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            {{-- Галерея изображений --}}
            <div class="product-gallery">
                <div class="main-image-display product__image-container mb-3">
                    {{-- Основное изображение --}}
                    @php
                        $mainImageFileName = null;
                        if ($product->images->count() > 0) {
                            $mainImageFileName = $product->images->first()->image_path;
                        } elseif (!empty($product->img)) {
                            $mainImageFileName = $product->img;
                        }
                    @endphp
                    @if($mainImageFileName)
                        <img src="{{ asset('media/images/' . $mainImageFileName) }}" class="product__image" id="mainProductImage" alt="{{ $product->title }}">
                    @else
                        {{-- Плейсхолдер, если изображений нет --}}
                        <div class="placeholder-image d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                    {{-- Миниатюры изображений --}}
                    <div class="thumbnail-images d-flex flex-wrap gap-2 justify-content-center">
                        @foreach($product->images as $image)
                            <img src="{{ asset('media/images/' . $image->image_path) }}" class="thumbnail-image rounded cursor-pointer" alt="{{ $product->title }}" data-image-path="{{ asset('media/images/' . $image->image_path) }}">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product__main-info">
                <h1 class="product__title">{{ $product->title }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="product-rating me-3 top-categories-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->rating ?? 0))
                                <i class="bi bi-star-fill"></i>
                            @elseif($i - 0.5 <= ($product->rating ?? 0))
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">{{ number_format($product->rating ?? 0, 1) }}</span>
                </div>
                
                <div class="product-status-badges mb-4">
                    @if($product->is_new)
                        <span class="badge bg-info me-2">Новинка</span>
                    @endif
                    @if($product->is_bestseller)
                        <span class="badge bg-success me-2">Хит продаж</span>
                    @endif
                    @if($product->on_sale)
                        <span class="badge bg-danger me-2">Скидка</span>
                    @endif
                    @if($product->in_stock)
                        <span class="badge bg-success">В наличии</span>
                    @else
                        <span class="badge bg-secondary">Нет в наличии</span>
                    @endif
                </div>
                
                <div class="product__price top-categories-price">
                    @if(isset($product->old_price) && $product->old_price > $product->price)
                        <del class="text-muted me-2">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</del>
                    @endif
                    <span class="price-value">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                    @if($product->on_sale && $product->discount_percent > 0)
                        <span class="badge bg-danger ms-2">-{{ round($product->discount_percent) }}%</span>
                    @endif
                </div>
                
                <div class="d-flex justify-content-start mt-4">
                    @if($product->in_stock)
                        <button class="product__add-to-cart" onclick="addToCart({{ $product->id }})" {{ !$product->in_stock ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus me-2"></i>
                            @if($product->in_stock)
                                Добавить в корзину
                            @else
                                Нет в наличии
                            @endif
                        </button>
                        <button class="btn-favorite ms-3" onclick="toggleFavorite({{ $product->id }})" data-product-id="{{ $product->id }}">
                            <i class="bi @auth{{ auth()->user()->favorites->contains($product->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}@else bi-heart @endauth"></i>
                        </button>
                    @else
                        <button class="product__add-to-cart" disabled>
                            <i class="bi bi-x-circle me-2"></i>Нет в наличии
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Описание</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Характеристики</button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom bg-white" id="productTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <div class="product__description">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <div class="product__characteristic">
                        @php
                            // Получаем разрешенные ключи характеристик для категории
                            // $allowedKeys = $product->category ? $product->category->allowedSpecKeys() : [];
                            // Группируем только разрешенные характеристики
                            // $groupedSpecs = $product->specifications->whereIn('spec_key', $allowedKeys)->groupBy('group');
                        @endphp
                        
                        @foreach($groupedSpecs as $group => $specs)
                            @php
                                // Фильтруем спецификации, исключая 'Бренд: Корпус' и оставляем только уникальные по spec_key
                                $filteredSpecs = $specs->filter(function ($spec) {
                                    return !($spec->spec_name === 'Бренд' && $spec->spec_value === 'Корпус');
                                })->unique('spec_key');
                            @endphp

                            {{-- Проверяем, есть ли спецификации в группе после фильтрации --}}
                            @if($filteredSpecs->count() > 0)
                                <div class="spec-group mb-4">
                                    <h4 class="mb-3">{{ $group }}</h4>
                                    <table class="table">
                                        <tbody>
                                            @foreach($filteredSpecs as $spec)
                                                <tr>
                                                    <td width="40%">{{ $spec->spec_name }}</td>
                                                    <td>{{ $spec->spec_value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach

                        @if(count($groupedSpecs) == 0)
                            <table>
                                <tr>
                                    <td>Категория</td>
                                    <td>{{ $product->product_type ?? 'Не указано' }}</td>
                                </tr>
                                <tr>
                                    <td>Страна-производитель</td>
                                    <td>{{ $product->country ?? 'Не указано' }}</td>
                                </tr>
                                <tr>
                                    <td>Цвет</td>
                                    <td>{{ $product->color ?? 'Не указано' }}</td>
                                </tr>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($similarProducts) && $similarProducts->count() > 0)
    <div class="similar-products mt-5">
        <h3 class="section-title mb-4">Похожие товары</h3>
        <div class="row">
            @foreach($similarProducts as $similarProduct)
                <div class="col-md-3 col-6 mb-4">
                    <div class="top-categories-card">
                        <a href="{{ route('catalog.product', $similarProduct->id) }}" class="text-decoration-none">
                            <div class="text-center mb-3">
                                @php
                                    $similarImageFileName = null;
                                    if ($similarProduct->images && $similarProduct->images->count() > 0) {
                                        $similarImageFileName = $similarProduct->images->first()->image_path;
                                    } elseif (!empty($similarProduct->img)) {
                                        $similarImageFileName = $similarProduct->img;
                                    }

                                    $similarImageUrl = null;
                                    if ($similarImageFileName) {
                                        $similarImageUrl = Vite::asset('resources/media/images/' . $similarImageFileName);
                                    }
                                @endphp
                                @if($similarImageUrl)
                                    <img src="{{ $similarImageUrl }}" class="top-categories-image" alt="{{ $similarProduct->title }}">
                                @else
                                    <div class="placeholder-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="top-categories-product-title">{{ $similarProduct->title }}</h5>
                        </a>
                        <div class="top-categories-price mt-2">
                            @if(isset($similarProduct->old_price) && $similarProduct->old_price > $similarProduct->price)
                                <del class="text-muted me-2">{{ number_format($similarProduct->old_price, 0, ',', ' ') }} ₽</del>
                            @endif
                            {{ number_format($similarProduct->price, 0, ',', ' ') }} ₽
                        </div>
                        <a href="{{ route('catalog.product', $similarProduct->id) }}" class="top-categories-btn mt-2">
                            Подробнее <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Блок рекомендаций --}}
    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="recommended-products mt-5">
            <h3 class="section-title mb-4">Рекомендуем из этой категории</h3>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
                @foreach($recommendedProducts as $recommendedProduct)
                    {{-- Отладочный вывод для рекомендованных товаров --}}
                    @php
                        $recommendedImageFileName = null;
                        // Сначала проверяем новую таблицу images
                        if ($recommendedProduct->images && $recommendedProduct->images->count() > 0) {
                            $recommendedImageFileName = $recommendedProduct->images->first()->image_path;
                        }
                        // Если в новой таблице нет, проверяем старое поле img
                        elseif (!empty($recommendedProduct->img)) {
                            $recommendedImageFileName = $recommendedProduct->img;
                        }

                        $recommendedImageUrl = null;
                        if ($recommendedImageFileName) {
                            $recommendedImageUrl = Vite::asset('resources/media/images/' . $recommendedImageFileName);
                        }
                    @endphp

                    {{-- Удаляем отладочный вывод --}}
                    {{-- <!-- Debug for Recommended Product: {{ $recommendedProduct->title }} (ID: {{ $recommendedProduct->id }}) --> --}}
                    {{-- <!-- Image Filename: {{ $recommendedImageFileName ?? 'N/A' }} --> --}}
                    {{-- <!-- Generated Image URL: {{ $recommendedImageUrl ?? 'N/A' }} --> --}}
                    {{-- <!-- Images Count (from loaded relation): {{ $recommendedProduct->images->count() ?? 'N/A' }} --> --}}

                    <div class="col">
                        <div class="top-categories-card h-100">
                            @if($recommendedProduct->discount_percent > 0)
                                <span class="top-categories-badge">-{{ (int)$recommendedProduct->discount_percent }}%</span>
                            @endif
                            {{-- Восстанавливаем оригинальную структуру и стили для изображения --}}
                            <div class="text-center mb-3" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                                @if($recommendedImageUrl)
                                    {{-- Используем оригинальный класс top-categories-image --}}
                                    <img src="{{ $recommendedImageUrl }}" alt="{{ $recommendedProduct->title }}" class="top-categories-image">
                                @else
                                    {{-- Восстанавливаем оригинальный плейсхолдер --}}
                                    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%; width: 100%;">
                                        <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                                        <div style="margin-top: 5px; color: #999; font-size: 0.8rem;">Нет изображения</div>
                                    </div>
                                @endif
                            </div>
                             <h5 class="top-categories-product-title">{{ $recommendedProduct->title }}</h5>
                             <div class="top-categories-rating mb-1">
                                  @for($i = 1; $i <= 5; $i++)
                                      @if($i <= $recommendedProduct->rating)
                                          <i class="bi bi-star-fill"></i>
                                      @elseif($i - 0.5 <= $recommendedProduct->rating)
                                          <i class="bi bi-star-half"></i>
                                      @else
                                          <i class="bi bi-star"></i>
                                      @endif
                                  @endfor
                              </div>
                              <div class="top-categories-price mb-2">
                                  {{ number_format($recommendedProduct->price, 0, ',', ' ') }} ₽
                                  @if($recommendedProduct->old_price && $recommendedProduct->old_price > $recommendedProduct->price)
                                      <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.8rem;">{{ number_format($recommendedProduct->old_price, 0, ',', ' ') }} ₽</span>
                                  @endif
                              </div>
                              <a href="{{ route('catalog.product', $recommendedProduct->id) }}" class="top-categories-btn">
                                  Подробнее <i class="bi bi-arrow-right"></i>
                              </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div id="successToast" class="toast success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товар добавлен в корзину</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="errorToast" class="toast error" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товара нет в наличии</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <div id="favoriteToast" class="toast success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товар добавлен в избранное</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="removeFavoriteToast" class="toast success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Товар удален из избранного</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    {{-- Toast для неавторизованных пользователей --}}
    <div id="authRequiredToast" class="toast info" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Пожалуйста, войдите или зарегистрируйтесь, чтобы выполнить это действие.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

</div>

<script>
    console.log("DOM fully loaded and parsed.");

    document.addEventListener('DOMContentLoaded', function () {
        console.log("DOMContentLoaded event fired.");
        const mainProductImage = document.getElementById('mainProductImage');
        const thumbnailImages = document.querySelectorAll('.thumbnail-image');

        console.log("Main image element:", mainProductImage);
        console.log("Thumbnail images found:", thumbnailImages.length);

        if (mainProductImage && thumbnailImages.length > 0) {
            thumbnailImages.forEach(thumbnail => {
                console.log("Adding click listener to thumbnail:", thumbnail);
                console.log("Thumbnail data-image-path:", thumbnail.getAttribute('data-image-path'));
                thumbnail.addEventListener('click', function () {
                    const newImagePath = this.getAttribute('data-image-path');
                    console.log("Thumbnail clicked. New image path:", newImagePath);
                    mainProductImage.src = newImagePath;
                    console.log("Main image src updated to:", mainProductImage.src);
                });
            });
        } else {
            if (!mainProductImage) {
                console.error("Error: Main product image element #mainProductImage not found.");
            }
            if (thumbnailImages.length === 0) {
                console.warn("Warning: No thumbnail images found with class .thumbnail-image.");
            }
        }
    });

function addToCart(productId) {
    // Проверяем, авторизован ли пользователь
    @guest
        // Если не авторизован, показываем сообщение и прерываем выполнение
        const authRequiredToast = new bootstrap.Toast(document.getElementById('authRequiredToast'));
        authRequiredToast.show();
        return;
    @endguest

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
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        } else {
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            toast.show();
        }
    })
    .catch(() => {
        const toast = new bootstrap.Toast(document.getElementById('errorToast'));
        toast.show();
    });
}

function toggleFavorite(productId) {
    // Проверяем, авторизован ли пользователь
    @guest
        // Если не авторизован, показываем сообщение и прерываем выполнение
        const authRequiredToast = new bootstrap.Toast(document.getElementById('authRequiredToast'));
        authRequiredToast.show();
        return;
    @endguest

    if(!productId) return;
    
    const button = document.querySelector(`button[data-product-id="${productId}"] i`);
    const isCurrentlyFavorite = button.classList.contains('bi-heart-fill');
    
    // Оптимистично обновляем UI сразу
    if (isCurrentlyFavorite) {
        // Если уже в избранном - удаляем оттуда (делаем сердечко пустым)
        button.classList.remove('bi-heart-fill');
        button.classList.remove('text-danger');
        button.classList.add('bi-heart');
    } else {
        // Если не в избранном - добавляем (делаем сердечко заполненным и красным)
        button.classList.remove('bi-heart');
        button.classList.add('bi-heart-fill');
        button.classList.add('text-danger');
    }
    
    fetch(`/favorites/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        throw new Error('Ошибка сети');
    })
    .then(data => {
        if (data.status === 'added') {
            const toast = new bootstrap.Toast(document.getElementById('favoriteToast'));
            toast.show();
        } else if (data.status === 'removed') {
            const toast = new bootstrap.Toast(document.getElementById('removeFavoriteToast'));
            toast.show();
        }
    })
    .catch(() => {
        // Возвращаем предыдущее состояние в случае ошибки
        if (isCurrentlyFavorite) {
            button.classList.add('bi-heart-fill');
            button.classList.add('text-danger');
            button.classList.remove('bi-heart');
        } else {
            button.classList.add('bi-heart');
            button.classList.remove('bi-heart-fill');
            button.classList.remove('text-danger');
        }
    });
}
</script>

<style>
.btn-favorite {
    background: transparent;
    border: 1px solid #dee2e6;
    border-radius: 50%;
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-favorite:hover {
    background-color: #f8f9fa;
    border-color: #d4d4d4;
}

.btn-favorite i {
    font-size: 1.4rem;
}

.btn-favorite i.text-danger {
    color: #dc3545 !important;
    filter: drop-shadow(0 0 1px rgba(0,0,0,0.1));
}

.btn-favorite i.bi-heart-fill {
    color: #dc3545 !important;
}

.product__add-to-cart {
    background: linear-gradient(95.73deg, #000 43.89%, #444 100%);
    color: white;
    border: none;
    border-radius: 5px;
    padding: 12px 24px;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

.product__add-to-cart:hover {
    opacity: 0.9;
}

/* Стили для уведомлений (toasts) */
.toast {
    /* Базовые стили тоста */
}

.toast.success {
    background-color: #d4edda; /* Светло-зеленый фон */
    color: #155724; /* Темно-зеленый текст */
    border-color: #c3e6cb;
}

.toast.error {
    background-color: #f8d7da; /* Светло-красный фон */
    color: #721c24; /* Темно-красный текст */
    border-color: #f5c6cb;
}

.toast.info { /* Добавляем стиль для уведомлений info */
    background-color: red; /* Светло-голубой фон */
    color: #0c5460; /* Темно-синий текст */
    border-color: red;
}

.toast-body {
    /* Стилизация тела тоста */
}

.btn-close-white {
    /* Стилизация кнопки закрытия */
    filter: invert(1) grayscale(100%) brightness(200%); /* Белый цвет */
}

/* Стилизация изображений продуктов на карточках */
.top-categories-image {
    max-height: 150px; /* Максимальная высота */
    width: auto; /* Ширина по авто */
    object-fit: contain; /* Сохраняем пропорции */
}

/* Плейсхолдер изображения */
.no-image-placeholder {
    background-color: #e9ecef;
    border-radius: 8px;
}

/* Стили для детальной страницы продукта */
.product__image-container {
    width: 100%; /* Основное изображение занимает всю ширину контейнера */
    max-height: 325px !important; /* Фиксированная высота контейнера */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa; /* Светлый фон для контейнера */
    border-radius: 8px;
    overflow: hidden; /* Скрываем все, что выходит за пределы */
}

.product__image {
    display: block; /* Убираем возможные лишние отступы */
    max-width: 100%; /* Изображение не должно превышать ширину контейнера */
    max-height: 100%; /* Изображение не должно превышать высоту контейнера */
    object-fit: contain; /* Масштабируем изображение, сохраняя пропорции, чтобы оно поместилось в контейнер без обрезки */
}

.placeholder-image {
    width: 100%;
    height: 400px;
    background-color: #e9ecef;
    border-radius: 8px;
}

.product__title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.product-rating i {
    color: #ffc107; /* Цвет звезд */
}

.product-status-badges .badge {
    font-size: 0.85rem;
    padding: 0.4em 0.6em;
}

.product__price .price-value {
    font-size: 2rem;
    font-weight: 700;
}

.product__price del {
    font-size: 1.2rem;
}

.product__description p {
    font-size: 1rem;
    line-height: 1.6;
}

.product__characteristic table {
    width: 100%;
    margin-bottom: 1rem;
}

.product__characteristic th, .product__characteristic td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.product__characteristic th {
    font-weight: 600;
    width: 30%; /* Ширина колонки с названием характеристики */
}

/* Переопределяем стили для групп характеристик */
.spec-group h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    border-bottom: 2px solid #000; /* Добавляем черную полосу */
    padding-bottom: 0.5rem;
}

.spec-group table {
    margin-bottom: 0;
}

.spec-group table tr:first-child td {
    border-top: none;
}

.similar-products .section-title {
    font-size: 1.5rem;
    font-weight: 700;
}

.top-categories-card .top-categories-image, .top-categories-card .placeholder-image {
    height: 180px; /* Задаем фиксированную высоту для карточек */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.top-categories-card .top-categories-image {
    max-height: 100%;
    width: auto;
    object-fit: contain;
}

.top-categories-card .placeholder-image i {
    font-size: 3rem;
    color: #ccc;
}

.top-categories-product-title {
    font-size: 1rem;
    font-weight: 600;
    margin-top: 0.5rem;
    min-height: 2.5rem; /* Фиксированная высота для заголовка */
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Ограничиваем 2 строками */
    -webkit-box-orient: vertical;
}

.top-categories-price {
    font-size: 1.1rem;
    font-weight: 700;
}

.top-categories-btn {
    display: inline-block;
    margin-top: 0.75rem;
    padding: 0.5rem 1rem;
    background-color: #000;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
}

.top-categories-btn:hover {
    background-color: #333;
    color: #fff;
}

/* Стили для галереи изображений на странице продукта */
.product-gallery {
    display: flex;
    flex-direction: column;
    align-items: center; /* Центрируем содержимое по горизонтали */
}

.main-image-display {
    width: 100%; /* Контейнер занимает всю доступную ширину */
    height: 400px; /* Фиксированная высота контейнера */
    display: flex; /* Используем flexbox для центрирования содержимого */
    align-items: center; /* Центрируем изображение по вертикали */
    justify-content: center; /* Центрируем изображение по горизонтали */
    padding: 20px; /* Добавляем внутренние отступы вокруг изображения */
    background-color: #fff; /* Опционально: белый фон контейнера */
    box-sizing: border-box; /* Учитываем padding и border в размерах */
    margin-bottom: 1rem; /* Отступ снизу от основного изображения до миниатюр */
    overflow: hidden; /* Скрываем все, что выходит за пределы */
}

.thumbnail-images {
    /* Flexbox стили уже добавлены в HTML */
}

.thumbnail-image {
    width: 60px; /* Фиксированный размер для миниатюр */
    height: 60px;
    object-fit: cover; /* Обрезаем изображение, чтобы оно вписывалось в квадрат */
    border: 2px solid #eee; /* Светлая рамка */
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.thumbnail-image:hover, .thumbnail-image.active {
    border-color: #000; /* Черная рамка при наведении или активности */
}

.product__main-info {
    background-color: #fff; /* Add a white background */
    border-radius: 8px; /* Match border radius of image container */
    display: flex; /* Use flexbox for content distribution */
    flex-direction: column; /* Stack items vertically */
    justify-content: space-between; /* Distribute space between items */
}

/* Стили для изображений в похожих товарах */
.top-categories-card .text-center {
    width: 100%; /* Контейнер занимает всю ширину карточки */
    height: 150px; /* Фиксированная высота контейнера для всех изображений */
    display: flex; /* Используем flexbox для центрирования */
    align-items: center; /* Центрируем по вертикали */
    justify-content: center; /* Центрируем по горизонтали */
    margin-bottom: 1rem; /* Отступ снизу */
    overflow: hidden; /* Скрываем все, что выходит за пределы */
}

.top-categories-image {
    max-width: 100%; /* Изображение не должно превышать ширину контейнера */
    max-height: 100%; /* Изображение не должно превышать высоту контейнера */
    object-fit: contain; /* Масштабируем изображение, сохраняя пропорции */
}
</style>
@endsection
