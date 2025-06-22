@extends('layouts.app')
@section('content')
<div class="container py-4">
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
            <div class="product-image-container border p-4 rounded bg-white text-center">
                @php
                    $imageFileName = null;
                    if (isset($product->images) && $product->images->count() > 0) {
                        $imageFileName = $product->images->first()->image_path;
                    } elseif (!empty($product->img)) {
                        $imageFileName = $product->img;
                    }

                    // Перебор всех возможных вариантов вывода картинки
                    $imageVariants = [];
                    if ($imageFileName) {
                        // Vite::asset
                        $imageVariants[] = Vite::asset('resources/media/images/' . $imageFileName);
                        // asset media/images
                        $imageVariants[] = asset('media/images/' . $imageFileName);
                        // asset storage
                        $imageVariants[] = asset('storage/' . $imageFileName);
                        // относительный путь
                        $imageVariants[] = '/media/images/' . $imageFileName;
                        $imageVariants[] = '/storage/' . $imageFileName;
                    }
                @endphp
                @php
                    $imgDisplayed = false;
                @endphp
                @foreach($imageVariants as $variant)
                    @if(!$imgDisplayed)
                        <img src="{{ $variant }}" class="img-fluid product-detail-image" alt="{{ $product->title }}" onerror="this.onerror=null;this.style.display='none';">
                        @php $imgDisplayed = true; @endphp
                    @endif
                @endforeach
                @if(!$imgDisplayed)
                    <div style="width: 300px; height: 300px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                        Нет фото
                    </div>
                @endif
            </div>
            @if(isset($product->images) && $product->images->count() > 1)
                <div class="d-flex mt-3 gap-2">
                    @foreach($product->images as $image)
                        @php
                            $thumbVariants = [];
                            $thumbFile = $image->image_path;
                            if ($thumbFile) {
                                $thumbVariants[] = Vite::asset('resources/media/images/' . $thumbFile);
                                $thumbVariants[] = asset('media/images/' . $thumbFile);
                                $thumbVariants[] = asset('storage/' . $thumbFile);
                                $thumbVariants[] = '/media/images/' . $thumbFile;
                                $thumbVariants[] = '/storage/' . $thumbFile;
                            }
                            $thumbDisplayed = false;
                        @endphp
                        @foreach($thumbVariants as $variant)
                            @if(!$thumbDisplayed)
                                <img src="{{ $variant }}" alt="{{ $product->title }}" style="width: 48px; height: 48px; object-fit: contain; border: 1px solid #eee; border-radius: 4px;" onerror="this.onerror=null;this.style.display='none';">
                                @php $thumbDisplayed = true; @endphp
                            @endif
                        @endforeach
                        @if(!$thumbDisplayed)
                            <div style="width: 48px; height: 48px; border: 1px solid #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px;">Нет фото</div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-lg-6">
            <h1 class="product-title mb-3">{{ $product->title }}</h1>
            
            <div class="d-flex align-items-center mb-3">
                <div class="product-rating me-3">
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
            
            <div class="product-price mb-4">
                @if($product->old_price && $product->old_price > $product->price)
                    <del class="text-muted me-2">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</del>
                @endif
                <span class="price-value">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                @if($product->discount_percent > 0)
                    <span class="badge bg-danger ms-2">-{{ round($product->discount_percent) }}%</span>
                @endif
            </div>
            
            <div class="product-actions mb-4">
                @if($product->in_stock)
                    <button class="btn btn-primary me-2" onclick="addToCart({{ $product->id }})">
                        <i class="bi bi-cart-plus me-2"></i>В корзину
                    </button>
                @else
                    <button class="btn btn-secondary me-2" disabled>
                        <i class="bi bi-x-circle me-2"></i>Нет в наличии
                    </button>
                @endif
                
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
            
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Описание</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Характеристики</button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>{{ $product->description }}</p>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    @foreach($groupedSpecs as $group => $specs)
                        <h4 class="specifications-group-title mb-3">{{ $group }}</h4>
                        <div class="specs-group mb-4">
                            <div class="row">
                                @foreach($specs as $spec)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex">
                                            <div class="spec-name text-muted me-2">{{ $spec->spec_name }}:</div>
                                            <div class="spec-value">{{ $spec->spec_value }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    @if($similarProducts->count() > 0)
    <div class="similar-products mt-5">
        <h3 class="section-title mb-4">Похожие товары</h3>
        <div class="row">
            @foreach($similarProducts as $similarProduct)
                <div class="col-md-3 col-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="card-body">
                            <a href="{{ route('product.show', $similarProduct->id) }}" class="text-decoration-none">
                                <div class="similar-product-image mb-3">
                                    @if($similarProduct->img)
                                        <img src="{{ asset('media/images/' . $similarProduct->img) }}" class="img-fluid" alt="{{ $similarProduct->title }}">
                                    @else
                                        <div class="placeholder-image d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="card-title">{{ $similarProduct->title }}</h5>
                            </a>
                            <div class="product-price mt-2">
                                @if($similarProduct->old_price && $similarProduct->old_price > $similarProduct->price)
                                    <del class="text-muted me-2">{{ number_format($similarProduct->old_price, 0, ',', ' ') }} ₽</del>
                                @endif
                                <span class="price-value">{{ number_format($similarProduct->price, 0, ',', ' ') }} ₽</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.product-detail-image {
    max-height: 400px;
    object-fit: contain;
}

.placeholder-image {
    height: 300px;
    background-color: #f8f9fa;
}

.product-title {
    font-size: 1.8rem;
    font-weight: 600;
}

.product-rating i {
    color: #ffc107;
}

.price-value {
    font-size: 1.8rem;
    font-weight: 600;
    color: #212529;
}

.specifications-group-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.similar-product-image {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.similar-product-image img {
    max-height: 100%;
    object-fit: contain;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
@endsection 