@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Избранные товары</h1>

    @if($favorites->isEmpty())
        <div class="alert alert-info">
            У вас пока нет избранных товаров
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($favorites as $product)
                <div class="col favorite-item" data-product-id="{{ $product->id }}">
                    <div class="card h-100 position-relative">
                        <form action="{{ route('favorites.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="btn position-absolute favorite-toggle" 
                                    style="top: 10px; right: 10px; z-index: 10;" 
                                    title="Убрать из избранного">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </button>
                        </form>
                        
                        <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
                            @if($product->img)
                                <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                     class="card-img-top h-100 w-100" 
                                     style="object-fit: contain;"
                                     alt="{{ $product->title }}">
                            @else
                                <div class="no-image-placeholder h-100 d-flex align-items-center justify-content-center bg-light">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text fs-4 fw-bold text-primary mb-3">{{ number_format($product->price, 0, ',', ' ') }} ₽</p>
                            <div class="d-grid">
                                <a href="{{ route('product.show', $product) }}" class="btn btn-primary">
                                    Подробнее
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.favorite-item {
    transition: all 0.3s ease-out;
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.favorite-toggle {
    background: none;
    border: none;
    padding: 0.5rem;
}

.favorite-toggle i {
    filter: drop-shadow(0 0 2px white);
}

.card-img-wrapper img {
    transition: transform 0.3s ease-in-out;
}

.card:hover .card-img-wrapper img {
    transform: scale(1.05);
}
</style>
@endsection 