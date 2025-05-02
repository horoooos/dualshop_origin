@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Результаты поиска</h1>

    <!-- Форма поиска -->
    <form action="{{ route('search') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="query" class="form-control" placeholder="Поиск товаров..." value="{{ $query }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Поиск</button>
            </div>
        </div>
    </form>

    @if($query)
        <p class="mb-4">Результаты поиска по запросу "{{ $query }}"</p>
    @endif

    @if($products->isEmpty())
        <div class="alert alert-info">
            По вашему запросу ничего не найдено
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        @if($product->img)
                            <img src="{{ asset('storage/' . $product->img) }}" class="card-img-top" alt="{{ $product->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ number_format($product->price, 2) }} ₽</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('product.show', $product) }}" class="btn btn-primary">Подробнее</a>
                                @auth
                                    <button class="btn btn-outline-danger favorite-toggle" data-product-id="{{ $product->id }}">
                                        <i class="bi bi-heart{{ auth()->user()->favorites->contains($product->id) ? '-fill' : '' }}"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@auth
@push('scripts')
<script>
document.querySelectorAll('.favorite-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        fetch(`/favorites/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const icon = this.querySelector('i');
            if (data.status === 'removed') {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
            } else {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
            }
        });
    });
});
</script>
@endpush
@endauth
@endsection 