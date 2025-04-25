@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Результаты поиска</h1>
    
    @if($query)
        <p>Результаты поиска для: "{{ $query }}"</p>
        
        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100">
                            @if($product->img)
                                <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->title }}</h5>
                                <p class="card-text">{{ $product->price }} ₽</p>
                                <a href="{{ route('product.show', $product->id) }}" 
                                   class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>По вашему запросу ничего не найдено.</p>
        @endif
    @else
        <p>Введите поисковый запрос для начала поиска.</p>
    @endif
</div>
@endsection 