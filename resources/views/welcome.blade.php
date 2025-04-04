<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dualshop</title>
    <!-- Убрал дублирующиеся подключения -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
@extends('Layouts.app')
@section('content')

<section class="product-banner position-relative overflow-hidden bg-light">
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-md-6 content-column">
        <h1 class="product-title">
          <a href="/catalog?filter=1" class="d-inline-block">
            <img src="{{ Vite::asset('resources/media/images/fire.png') }}" alt="Apple" class="apple-logo mb-4">
          </a>
          Apple iPhone 15
        </h1>
        <p class="product-features lead mb-4">
          Минималистичный дизайн,<br>
          отличные камеры и мощный<br> процессор
        </p>
        <p class="product-description mb-5">
          Модель смартфона представлена в трех вариантах: стандартный iPhone 15,
          увеличенный iPhone 15 Plus, премиальный iPhone 15 Pro и, наконец,
          флагманская модель - iPhone 15 Pro Max.
        </p>
        <a href="/catalog?filter=1" class="btn btn-dark btn-lg px-5 rounded-1 btn-banner">Купить</a>
      </div>

      <div class="col-md-6 image-column">
        <div class="image-wrapper">
          <a href="/catalog?filter=1">
            <img src="{{ Vite::asset('resources/media/images/phone.png') }}" alt="iPhone 15" class="product-image">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="categories-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <h2 class="section-title">Популярные категории</h2>
            </div>
            <div class="col-md-4">
                <h2 class="section-title">Сезонные товары</h2>
            </div>
            <div class="col-md-4">
                <h2 class="section-title">Акции и скидки</h2>
            </div>
        </div>

        <div class="row g-4">
            <!-- Популярные категории -->
            <div class="col-lg-4 col-md-6">
                @if(isset($categories) && $categories->isNotEmpty())
                    @foreach($categories as $category)
                    <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    <img src="{{ Vite::asset('resources/media/images/' . $category->img) }}" 
                                         class="category-img" 
                                         alt="{{ $category->name ?? 'Категория' }}">
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    <li class="category-item">{{ $category->name ?? 'Название категории' }}</li>
                                    <!-- Убрал подкатегории, так как нет parent_id -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>Нет доступных категорий</p>
                @endif
            </div>

            <!-- Сезонные товары -->
            <div class="col-lg-4 col-md-6">
                @if(isset($seasonalProducts) && $seasonalProducts->isNotEmpty())
                    @foreach($seasonalProducts as $product)
                    <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                         class="category-img" 
                                         alt="{{ $product->title ?? 'Товар' }}">
                                    @if($product->is_seasonal ?? false)
                                    <div class="new-product__badge">Сезон</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    <li class="category-item">{{ $product->title ?? 'Название товара' }}</li>
                                    <li class="category-item">{{ $product->price ?? 0 }} ₽</li>
                                    <li class="category-item mt-2">
                                        <a href="/product/{{ $product->id ?? '' }}" class="btn btn-sm btn-outline-success">Подробнее</a>
                                        @auth
                                        <button onclick="addToCart({{ $product->id ?? '' }})" class="btn btn-sm btn-success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        @endauth
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>Нет сезонных товаров</p>
                @endif
            </div>

            <!-- Акции -->
            <div class="col-lg-4 col-md-6">
                @if(isset($promotionProducts) && $promotionProducts->isNotEmpty())
                    @foreach($promotionProducts as $product)
                    <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                         class="category-img" 
                                         alt="{{ $product->title ?? 'Товар' }}">
                                    <div class="new-product__badge bg-danger">-{{ $product->discount ?? 0 }}%</div>
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    <li class="category-item">{{ $product->title ?? 'Название товара' }}</li>
                                    <li class="category-item">
                                        <span class="text-danger">{{ $product->discounted_price ?? $product->price ?? 0 }} ₽</span>
                                        @if($product->discount ?? false)
                                        <del class="text-muted">{{ $product->price ?? 0 }} ₽</del>
                                        @endif
                                    </li>
                                    <li class="category-item mt-2">
                                        <a href="/product/{{ $product->id ?? '' }}" class="btn btn-sm btn-outline-success">Подробнее</a>
                                        @auth
                                        <button onclick="addToCart({{ $product->id ?? '' }})" class="btn btn-sm btn-success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        @endauth
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>Нет товаров по акции</p>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="container">
    <section class="about">
        <h3 class="about__title text-start">Dualshop – всё для технологий</h3>
        <p class="about__text-block">
            Добро пожаловать в Dualshop! Мы предлагаем широкий ассортимент техники и аксессуаров, чтобы вы всегда оставались на пике технологий.
        </p>
        <p class="about__text-block">
            У нас вы найдете последние новинки электроники от мировых брендов, отличные цены и гарантированное качество. Доставка осуществляется по всей России, а также доступны услуги самовывоза.
        </p>
    </section>

    <section class="why-choose-us my-5">
        <h3 class="about__title text-start">Почему стоит выбрать нас?</h3>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card text-center">
                        <img src="{{ Vite::asset('resources/media/images/preim (1).svg') }}" class="card-img-top" alt="Широкий ассортимент">
                        <div class="card-body">
                            <h5 class="card-title">Широкий ассортимент</h5>
                            <p class="card-text">Товары от ведущих брендов.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card text-center">
                        <img src="{{ Vite::asset('resources/media/images/preim (3).svg') }}" class="card-img-top" alt="Конкурентные цены">
                        <div class="card-body">
                            <h5 class="card-title">Конкурентные цены</h5>
                            <p class="card-text">Регулярные акции и скидки.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card text-center">
                        <img src="{{ Vite::asset('resources/media/images/preim(2).svg') }}" class="card-img-top" alt="Гарантия качества">
                        <div class="card-body">
                            <h5 class="card-title">Гарантия качества</h5>
                            <p class="card-text">На все товары.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="slider">
        <h3 class="about__title text-start">Популярные товары</h3>
        @if(isset($products) && $products->isNotEmpty())
        <div id="carouselExampleCaptions" class="carousel carousel-dark slide">
            <div class="carousel-indicators pt-4">
                @foreach($products as $product)
                <button type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"
                        aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($products as $product)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" alt="{{ $product->title ?? 'Товар' }}" class="product__image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $product->title ?? 'Название товара' }}</h5>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        @else
        <p>Нет доступных продуктов для отображения.</p>
        @endif
    </section>

    <section class="new-products">
        <div class="container">
            <h3 class="about__title text-start">Новинки</h3>
            <div class="new-products__grid">
                @if(isset($newProducts) && $newProducts->isNotEmpty())
                    @foreach($newProducts as $product)
                    <div class="new-product__card">
                        <div class="new-product__image-container">
                            <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                 alt="{{ $product->title ?? 'Товар' }}" 
                                 class="new-product__image">
                            <div class="new-product__badge">New</div>
                        </div>
                        <div class="new-product__info">
                            <h4 class="new-product__title">{{ $product->title ?? 'Название товара' }}</h4>
                            <p class="new-product__price">{{ $product->price ?? 0 }} ₽</p>
                            <div class="new-product__actions">
                                <a href="/product/{{ $product->id ?? '' }}" class="btn btn-outline-success">Подробнее</a>
                                @auth
                                    <button onclick="addToCart({{ $product->id ?? '' }})" class="btn btn-success">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>Нет новинок</p>
                @endif
            </div>
        </div>
    </section>
</div>

@auth
<div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Успешно</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-success text-white">
            Товар успешно добавлен в корзину
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Ошибка</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-danger text-white">
            Товара нет в наличии
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
</script>

<style>
/* Ваши стили остаются без изменений */
</style>
@endauth

@endsection
</body>
</html>