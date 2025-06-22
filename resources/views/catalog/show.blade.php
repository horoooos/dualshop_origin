@extends('layouts.app')
@section('content')

{{-- Debug: показываем содержимое $recommendedProducts --}}
<pre style="background:#222;color:#fff;padding:10px;max-height:300px;overflow:auto;">$recommendedProducts = {{ var_export($recommendedProducts, true) }}</pre>
{{-- Debug: все товары этой категории --}}
<pre style="background:#222;color:#fff;padding:10px;max-height:300px;overflow:auto;">$productsInCategory = {{ var_export(\App\Models\Product::where('category_id', $product->category_id)->get()->toArray(), true) }}</pre>

<section class="catalog container pt-4 pb-5">
    <div class="row">
        <!-- Хлебные крошки -->
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog.categories') }}">Каталог</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('catalog.category', $product->category->id) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация о товаре -->
        <div class="col-12">
            <div class="product__main-info bg-white rounded shadow-sm mb-4">
                <div class="row">
                    <!-- Изображение товара -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        {{-- Галерея изображений --}}
                        <div class="product-gallery">
                            <div class="main-image-display product__image-container mb-3">
                                {{-- Основное изображение --}}
                                @if($product->images->count() > 0)
                                    {{-- Используем первое изображение как основное --}}
                                    <img src="{{ Vite::asset('resources/media/images/' . $product->images->first()->image_path) }}" class="product__image" id="mainProductImage" alt="{{ $product->title }}">
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
                                        {{-- Проверяем, существует ли файл изображения --}}
                                        @php
                                            $imagePath = 'resources/media/images/' . $image->image_path;
                                            $absoluteImagePath = public_path($imagePath);
                                            $imageUrl = Vite::asset($imagePath);
                                        @endphp
                                        @if(file_exists($absoluteImagePath))
                                            <img src="{{ $imageUrl }}" class="thumbnail-image rounded cursor-pointer" 
                                                alt="{{ $product->title }}" data-image-path="{{ $imageUrl }}">
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Информация о товаре -->
                    <div class="col-md-7">
                        <h1 class="product-titlee mb-4">{{ $product->title }}</h1>
                        
                        <div class="d-flex align-items-center mb-3">
                            <!-- Рейтинг товара -->
                            <div class="top-categories-rating me-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $product->rating)
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            
                            <!-- Артикул товара -->
                            <div class="text-muted small">Артикул: {{ $product->id }}</div>
                            
                 

                        </div>
                        
                        <!-- Статус наличия -->
                        @if($product->in_stock)
                            <div class="product-availability text-success mb-3">
                                <i class="bi bi-check-circle-fill"></i> В наличии
                            </div>
                        @else
                            <div class="product-availability text-danger mb-3">
                                <i class="bi bi-x-circle-fill"></i> Нет в наличии
                            </div>
                        @endif
                        
                        <!-- Новый блок цены и действий -->
                        <div class="product-purchase-block d-flex flex-wrap align-items-center mb-4 gap-3">
                            <div class="product-price-info flex-grow-1">
                                <div class="product-price-big mb-1">
                                    {{ number_format($product->price, 0, ',', ' ') }} <span class="rouble">₽</span>
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <span class="old-price ms-2">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</span>
                                    @endif
                                </div>
                                <div class="product-credit-small">от {{ number_format($product->price / 12, 0, ',', ' ') }} ₽/мес</div>
                            </div>
                            <button onclick="@auth addToCart({{ $product->id }}) @else window.location='{{ route('register') }}' @endauth" class="btn-buy-lg">
                                <i class="bi bi-cart-plus me-2"></i> В корзину
                            </button>
                            <button onclick="@auth toggleFavorite({{ $product->id }}) @else window.location='{{ route('register') }}' @endauth" class="btn-favorite-lg" data-product-id="{{ $product->id }}">
                                <i class="bi bi-heart @auth{{ auth()->user()->favorites->contains($product->id) ? '-fill text-danger' : '' }}@endauth"></i>
                            </button>
                        </div>
                        
                        <!-- Краткие характеристики -->
                        @if(count($specifications) > 0)
                            <div class="short-specs mb-4">
                                <h5 class="mb-3">Краткие характеристики</h5>
                                <ul class="list-unstyled">
                                    @php
                                        $specList = reset($specifications);
                                        $counter = 0;
                                    @endphp
                                    @foreach($specList as $spec)
                                        @if($counter < 5)
                                            <li class="mb-2 d-flex">
                                                <span class="text-muted" style="width: 40%;">{{ $spec->spec_name }}:</span> 
                                                <span class="fw-medium">{{ $spec->spec_value }}</span>
                                            </li>
                                            @php $counter++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                                <a href="#specifications" class="text-decoration-none">Все характеристики <i class="bi bi-arrow-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Описание товара -->
            @if($product->description)
                <div class="product__description bg-white rounded shadow-sm p-4 mb-4">
                    <h4>Описание</h4>
                    <p>{{ $product->description }}</p>
                </div>
            @endif
            
            <!-- Характеристики товара -->
            <div class="product__characteristic bg-white rounded shadow-sm p-4 mb-4" id="specifications">
                <h4 class="mb-4">Характеристики</h4>
                @if(isset($groupedSpecs) && count($groupedSpecs) > 0)
                    <div class="accordion custom-accordion" id="specificationsAccordion">
                        @foreach($groupedSpecs as $group => $specs)
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header" id="heading{{ Str::slug($group ?? 'Основные характеристики') }}">
                                    <button class="accordion-button custom-accordion-btn {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($group ?? 'Основные характеристики') }}" 
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ Str::slug($group ?? 'Основные характеристики') }}">
                                        {{ $group ?? 'Основные характеристики' }}
                                    </button>
                                </h2>
                                <div id="collapse{{ Str::slug($group ?? 'Основные характеристики') }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                        aria-labelledby="heading{{ Str::slug($group ?? 'Основные характеристики') }}" data-bs-parent="#specificationsAccordion">
                                    <table class="table table-borderless specs-table mb-0">
                                        <tbody>
                                            @foreach($specs as $spec)
                                                <tr>
                                                    <td width="45%" class="py-3 text-muted">{{ $spec->spec_name }}</td>
                                                    <td class="py-3 fw-bold text-dark">{{ $spec->spec_value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Характеристики этого товара отсутствуют.</div>
                @endif
            </div>
            
            <!-- Похожие товары -->
            @if(count($relatedProducts) > 0)
                <h4 class="mb-3">Похожие товары</h4>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col">
                            <div class="top-categories-card h-100">
                                @if($relatedProduct->discount_percent > 0)
                                    <span class="top-categories-badge">-{{ (int)$relatedProduct->discount_percent }}%</span>
                                @endif
                                <div class="text-center mb-3" style="height: 160px; display: flex; align-items: center; justify-content: center;">
                                    @if(!empty($relatedProduct->img) && Str::startsWith($relatedProduct->img, 'http'))
                                        <img src="{{ $relatedProduct->img }}" alt="{{ $relatedProduct->title }}" class="top-categories-image">
                                    @elseif(!empty($relatedProduct->img) && file_exists(public_path('resources/media/images/' . $relatedProduct->img)))
                                        <img src="{{ asset('resources/media/images/' . $relatedProduct->img) }}" alt="{{ $relatedProduct->title }}" class="top-categories-image">
                                    @else
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                            <div style="margin-top: 5px; color: #999; font-size: 0.8rem;">Нет изображения</div>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="top-categories-product-title">{{ $relatedProduct->title }}</h5>
                                <div class="top-categories-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $relatedProduct->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $relatedProduct->rating)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="top-categories-price">
                                    {{ number_format($relatedProduct->price, 0, ',', ' ') }} ₽
                                    @if($relatedProduct->old_price && $relatedProduct->old_price > $relatedProduct->price)
                                        <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.8rem;">{{ number_format($relatedProduct->old_price, 0, ',', ' ') }} ₽</span>
                                    @endif
                                </div>
                                <a href="{{ route('catalog.product', $relatedProduct->id) }}" class="top-categories-btn">
                                    Подробнее <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

@auth
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
    
    const button = document.querySelector(`button[data-product-id="${productId}"] i`);
    const isCurrentlyFavorite = button.classList.contains('bi-heart-fill');
    
    // Оптимистично обновляем UI
    if (isCurrentlyFavorite) {
        button.classList.remove('bi-heart-fill');
        button.classList.remove('text-danger');
        button.classList.add('bi-heart');
    } else {
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
    .catch(error => console.error('Error toggling favorite:', error));
}
</script>
@endauth

<style>
/* Стили для фильтров */
.filter-title {
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.filter-block {
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.filter-block:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

/* Кнопка избранного */
.btn-favorite {
    background: transparent;
    border: 1px solid #dee2e6;
    border-radius: 50%;
    width: 36px;
    height: 36px;
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
    font-size: 1.2rem;
}

/* Адаптивность */
@media (max-width: 991px) {
    .product-image-container {
        height: 350px;
    }
}

@media (max-width: 767px) {
    .product-image-container {
        height: 300px;
    }
}

.custom-accordion .accordion-item {
    border: none;
    background: transparent;
}
.custom-accordion-btn {
    background: #f9f9f9;
    font-weight: 500;
    font-size: 1.15rem;
    color: #02111b;
    border-radius: 8px;
    box-shadow: none;
    transition: background 0.2s;
    padding: 0.9rem 1.2rem;
}
.custom-accordion-btn:not(.collapsed) {
    background: #fff;
    color: #000;
}
.specs-table tr:nth-child(odd) {
    background-color: #f8f9fa;
}
.specs-table td {
    font-size: 1rem;
    vertical-align: middle;
    border: none;
}
.product-purchase-block {
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.product-price-info {
    min-width: 180px;
}
.product-price-big {
    font-size: 15rem;
    font-weight: 600;
    color: #000;
    line-height: 1.1;
}
.rouble {
    font-size: 1.3rem;
    font-weight: 600;
}
.old-price {
    color: #aaa;
    text-decoration: line-through;
    font-size: 1.1rem;
    font-weight: 400;
}
.product-credit-small {
    font-size: 1rem;
    color: #888;
}
.btn-buy-lg {
    background: #000;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 14px 32px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.btn-buy-lg:hover {
    background: #222;
}
.btn-favorite-lg {
    background: #fff;
    color: #000;
    border: 1px solid #eee;
    border-radius: 8px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    transition: border 0.2s, color 0.2s;
}
.btn-favorite-lg:hover {
    border: 1.5px solid #000;
    color: #d00;
}
@media (max-width: 768px) {
    .product-purchase-block {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    .btn-buy-lg, .btn-favorite-lg {
        width: 100%;
        justify-content: center;
    }
}
.custom-accordion .accordion-item {
    border: none;
    background: transparent;
}
.custom-accordion-btn {
    background: #f9f9f9;
    font-weight: 500;
    font-size: 1.15rem;
    color: #02111b;
    border-radius: 8px;
    box-shadow: none;
    transition: background 0.2s;
    padding: 0.9rem 1.2rem;
}
.custom-accordion-btn:not(.collapsed) {
    background: #fff;
    color: #000;
}
.specs-table tr:nth-child(odd) {
    background-color: #f8f9fa;
}
.specs-table td {
    font-size: 1rem;
    vertical-align: middle;
    border: none;
    padding: 0.9rem 0.7rem;
}
.specs-table td.fw-bold {
    font-weight: 700;
    color: #111;
}
.product__characteristic h4,
.product__characteristic .custom-accordion-btn,
.product__characteristic .accordion-button,
.product__characteristic .accordion-header,
.product__characteristic .accordion-body,
.product__characteristic .specs-table td,
.product__description h4,
.product__description p,
.btn-buy-lg,
.btn-favorite-lg,
.product-titlee,
.product-credit-small,
.product-price-info,
.short-specs,
.short-specs h5,
.short-specs ul,
.short-specs li,
.short-specs a {
    font-size: 1rem !important;
}
.custom-accordion-btn {
    padding: 0.7rem 1rem;
}
.product-price-big {
    font-size: 2.5rem !important;
    font-weight: 600;
    color: #000;
    line-height: 1.1;
}
</style>

@endsection

<body>
{{-- Debug: показываем содержимое $recommendedProducts --}}
<pre style="background:#222;color:#fff;padding:10px;max-height:300px;overflow:auto;">$recommendedProducts = {{ var_export($recommendedProducts, true) }}</pre>
{{-- Debug: все товары этой категории --}}
<pre style="background:#222;color:#fff;padding:10px;max-height:300px;overflow:auto;">$productsInCategory = {{ var_export(\App\Models\Product::where('category_id', $product->category_id)->get()->toArray(), true) }}</pre>
</body>

@if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
<div class="container mb-5">
    <h3 class="section-title mb-4">Рекомендуем из этой категории</h3>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        @foreach($recommendedProducts as $recProduct)
            <div class="col">
                <div class="top-categories-card h-100">
                    @if($recProduct->discount_percent > 0)
                        <span class="top-categories-badge">-{{ (int)$recProduct->discount_percent }}%</span>
                    @endif
                    <div class="text-center mb-3" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                        @if(!empty($recProduct->img) && Str::startsWith($recProduct->img, 'http'))
                            <img src="{{ $recProduct->img }}" alt="{{ $recProduct->title }}" class="top-categories-image">
                        @elseif(!empty($recProduct->img) && file_exists(public_path('resources/media/images/' . $recProduct->img)))
                            <img src="{{ asset('resources/media/images/' . $recProduct->img) }}" alt="{{ $recProduct->title }}" class="top-categories-image">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                                <div style="margin-top: 5px; color: #999; font-size: 0.8rem;">Нет изображения</div>
                            </div>
                        @endif
                    </div>
                    <h5 class="top-categories-product-title">{{ $recProduct->title }}</h5>
                    <div class="top-categories-rating mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $recProduct->rating)
                                <i class="bi bi-star-fill"></i>
                            @elseif($i - 0.5 <= $recProduct->rating)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="top-categories-price mb-2">
                        {{ number_format($recProduct->price, 0, ',', ' ') }} ₽
                        @if($recProduct->old_price && $recProduct->old_price > $recProduct->price)
                            <span class="text-muted text-decoration-line-through ms-2" style="font-size: 0.8rem;">{{ number_format($recProduct->old_price, 0, ',', ' ') }} ₽</span>
                        @endif
                    </div>
                    <a href="{{ route('catalog.product', $recProduct->id) }}" class="top-categories-btn">
                        Подробнее <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif 