@extends('layouts.app')
@section('content')

@php
    use Illuminate\Support\Facades\Log;
    if(isset($categories)) {
        Log::info('Categories in view:', ['categories' => $categories->toArray()]);
    } else {
        Log::info('Categories variable is not set in view');
    }
@endphp

<section class="product-banner position-relative overflow-hidden bg-light">
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-md-6 content-column">
        <h1 class="product-title1">
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
            <img src="{{ Vite::asset('resources/media/images/phone.png') }}" alt="iPhone 15" class="product-image1">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="categories-section py-5">
    <div class="container">
        <!-- <div class="row mb-4">
            <div class="col-md-4">
                <h2 class="section-title">Популярные категории</h2>
            </div>
            <div class="col-md-4">
                <h2 class="section-title">Сезонные товары</h2>
            </div>
            <div class="col-md-4">
                <h2 class="section-title">Акции и скидки</h2>
            </div>
        </div> -->

        <div class="row g-4">
            <!-- Популярные категории -->
            <div class="col-lg-4 col-md-6">
            <h2 class="section-title">Популярные категории</h2>
            <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    @php
                                        $randomCategory = \App\Models\Category::inRandomOrder()->first();
                                        $randomProduct = $randomCategory ? $randomCategory->products()->inRandomOrder()->first() : null;
                                    @endphp
                                    @if($randomProduct)
                                        <a href="{{ route('product.show', $randomProduct->id) }}">
                                            <img src="{{ Vite::asset('resources/media/images/' . $randomProduct->img) }}" 
                                                 class="category-img" 
                                                 alt="{{ $randomProduct->title }}">
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    @php
                                        $categories = \App\Models\Category::inRandomOrder()->take(5)->get();
                                    @endphp
                                    @if($categories->count() > 0)
                                        @foreach($categories as $category)
                                            <li class="category-item">
                                                <a href="{{ route('catalog') }}?filter={{ $category->id }}" 
                                                   class="category-list {{ request()->query('filter') == $category->id ? 'active' : '' }}">
                                                    {{ $category->product_type }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="category-item">Нет доступных категорий</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Сезонные товары -->
            <div class="col-lg-4 col-md-6">
            <h2 class="section-title">Сезонные товары</h2>
                <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    @php
                                        $randomCategory = \App\Models\Category::inRandomOrder()->first();
                                        $randomProduct = $randomCategory ? $randomCategory->products()->inRandomOrder()->first() : null;
                                    @endphp
                                    @if($randomProduct)
                                        <a href="{{ route('product.show', $randomProduct->id) }}">
                                            <img src="{{ Vite::asset('resources/media/images/' . $randomProduct->img) }}" 
                                                 class="category-img" 
                                                 alt="{{ $randomProduct->title }}">
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    @php
                                        $categories = \App\Models\Category::inRandomOrder()->take(5)->get();
                                    @endphp
                                    @if($categories->count() > 0)
                                        @foreach($categories as $category)
                                            <li class="category-item">
                                                <a href="{{ route('catalog') }}?filter={{ $category->id }}" 
                                                   class="category-list {{ request()->query('filter') == $category->id ? 'active' : '' }}">
                                                    {{ $category->product_type }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="category-item">Нет доступных категорий</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Акции -->
            <div class="col-lg-4 col-md-6">
            <h2 class="section-title">Акции и скидки</h2>
                <div class="category-card">
                        <div class="row g-3 h-100">
                            <div class="col-5">
                                <div class="image-container">
                                    @php
                                        $randomCategory = \App\Models\Category::inRandomOrder()->first();
                                        $randomProduct = $randomCategory ? $randomCategory->products()->inRandomOrder()->first() : null;
                                    @endphp
                                    @if($randomProduct)
                                        <a href="{{ route('product.show', $randomProduct->id) }}">
                                            <img src="{{ Vite::asset('resources/media/images/' . $randomProduct->img) }}" 
                                                 class="category-img" 
                                                 alt="{{ $randomProduct->title }}">
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-7">
                                <ul class="category-list">
                                    @php
                                        $categories = \App\Models\Category::inRandomOrder()->take(5)->get();
                                    @endphp
                                    @if($categories->count() > 0)
                                        @foreach($categories as $category)
                                            <li class="category-item">
                                                <a href="{{ route('catalog') }}?filter={{ $category->id }}" 
                                                   class="category-list {{ request()->query('filter') == $category->id ? 'active' : '' }}">
                                                    {{ $category->product_type }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="category-item">Нет доступных категорий</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

<section class="brands-section py-5 mt-5">
  <div class="container">
    <div class="row g-3 justify-content-center">
      <!-- Логотип 1 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (1).png') }}" alt="Brand 1" class="brand-logo">
        </div>
      </div>

      <!-- Логотип 2 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (2).png') }}" alt="Brand 2" class="brand-logo">
        </div>
      </div>

      <!-- Логотип 3 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (3).png') }}" alt="Brand 3" class="brand-logo">
        </div>
      </div>

      <!-- Логотип 4 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (4).png') }}" alt="Brand 4" class="brand-logo">
        </div>
      </div>

      <!-- Логотип 5 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (5).png') }}" alt="Brand 5" class="brand-logo">
        </div>
      </div>

      <!-- Логотип 6 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <img src="{{ Vite::asset('resources/media/images/logo_brand (6).png') }}" alt="Brand 6" class="brand-logo">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="products-section">
  <div class="container">
    <div class="row g-4">
      @if(isset($products) && $products->isNotEmpty())
        @foreach($products as $product)
          <div class="col-xl-4 col-md-6">
            <div class="product-card position-relative">
              <img src="{{ Vite::asset('resources/media/images/bxs_hot.png') }}" class="product-badge">
              <div class="row h-100 g-0 pe-4">
                <div class="col-6">
                  <div class="product-content">
                    <span class="product-timer">Еще 4 часа скидка</span>
                    <h3 class="product-title-cart">{{ $product->title }}<br></h3>
                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary product-detail-btn mt-2">Подробнее</a>
                  </div>
                </div>
                <div class="col-6">
                  <div class="product-image-container">
                    <a href="{{ route('product.show', $product->id) }}">
                      <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                           class="product-image" 
                           alt="{{ $product->title }}">
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <!-- Дополнительная карточка "Показать больше" -->
        <div class="col-xl-4 col-md-6">
            <div class="product-card position-relative d-flex align-items-center justify-content-center text-center h-100 show-more-card">
                <a href="{{ route('catalog') }}" class="product-detail-btn1 show-more-btn">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    Показать больше
                </a>
            </div>
        </div>

      @else
        <div class="col-12">
          <p class="text-center">Нет доступных товаров</p>
        </div>
      @endif
    </div>
  </div>
</section>

<section class="top-categories-section">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="top-categories-title">Популярные товары</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('catalog') }}" class="top-categories-link">
                    Перейти в каталог
                    <i class="bi bi-chevron-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="top-categories-grid">
            <div class="row g-4">
                @if(isset($topCategoriesProducts) && $topCategoriesProducts->isNotEmpty())
                    @foreach($topCategoriesProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="top-categories-card">
                            <div class="top-categories-badge">{{ $product->is_seasonal ? 'HOT' : '911' }}</div>
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                     class="top-categories-image" 
                                     alt="{{ $product->title }}">
                            </a>
                            <h3 class="top-categories-product-title">{{ $product->title }}</h3>
                            <div class="top-categories-rating">
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
                            <div class="top-categories-price">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                            <a href="{{ route('product.show', $product->id) }}" class="top-categories-btn">
                                Подробнее
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-center">Нет доступных товаров</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
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
@endauth

@endsection