@extends('layouts.app')

@section('content')
    <div class="product">
        <div class="container">
            <div class="product__image-container">
                <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                     alt="{{ $product->title }}" 
                     class="product__image">
            </div>

            <div class="product__main-info">
                <h1 class="product__title">{{ $product->title }}</h1>
                <div class="product__price">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                <div class="product__credit">от {{ number_format($product->price / 12, 0, ',', ' ') }} ₽/мес</div>
                @auth
                    <div class="d-flex justify-content-start">
                        <button class="product__add-to-cart" onclick="addToCart({{ $product->id }})">
                            Добавить в корзину
                        </button>
                    </div>
                @endauth
            </div>

            <div class="product__description">
                <h4>Описание</h4>
                <p>{{ $product->description ?? 'Описание отсутствует' }}</p>
            </div>

            <div class="product__characteristic">
                <table>
                    <tr>
                        <td>Категория</td>
                        <td>{{ $product->product_type }}</td>
                    </tr>
                    <tr>
                        <td>Страна-производитель</td>
                        <td>{{ $product->country }}</td>
                    </tr>
                    <tr>
                        <td>Цвет</td>
                        <td>{{ $product->color }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @auth
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
    </div>

    <script>
    function addToCart(productId) {
        fetch(`/add-to-cart/${productId}`)
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
