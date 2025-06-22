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
let timer;
const searchInput = document.querySelector('input[name=\"query\"]');
const resultsBox = document.createElement('div');
resultsBox.className = 'autocomplete-results';
searchInput.parentNode.appendChild(resultsBox);

searchInput.addEventListener('input', function() {
    clearTimeout(timer);
    const query = this.value.trim();
    if (query.length < 2) {
        resultsBox.innerHTML = '';
        resultsBox.style.display = 'none';
        return;
    }
    timer = setTimeout(() => {
        fetch(`/catalog/search?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    resultsBox.innerHTML = '<div class=\"autocomplete-item\">Ничего не найдено</div>';
                } else {
                    resultsBox.innerHTML = data.map(item =>
                        `<a href=\"/catalog/${item.id}\" class=\"autocomplete-item\">${item.title}</a>`
                    ).join('');
                }
                resultsBox.style.display = 'block';
            });
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!resultsBox.contains(e.target) && e.target !== searchInput) {
        resultsBox.style.display = 'none';
    }
});
</script>
<style>
.autocomplete-results {
    position: absolute;
    background: #fff;
    border: 1px solid #ddd;
    width: 100%;
    z-index: 1000;
    max-height: 250px;
    overflow-y: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.autocomplete-item {
    padding: 8px 12px;
    cursor: pointer;
    color: #333;
    text-decoration: none;
    display: block;
}
.autocomplete-item:hover {
    background: #f0f0f0;
}
</style>
@endpush
@endauth
@endsection 