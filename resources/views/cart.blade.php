@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Корзина</h1>
    
    @if($items->isEmpty())
        <div class="alert alert-info">
            Ваша корзина пуста
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <img src="{{ Vite::asset('resources/media/images/' . $item->product->img) }}" 
                                     alt="{{ $item->product->title }}" 
                                     style="max-width: 100px;">
                            </td>
                            <td>{{ $item->product->title }}</td>
                            <td>
                                @if($item->product->discount > 0)
                                    <span class="text-decoration-line-through">
                                        {{ number_format($item->product->price, 2) }} ₽
                                    </span>
                                    <br>
                                    <span class="text-danger">
                                        {{ number_format($item->product->price * (1 - $item->product->discount / 100), 2) }} ₽
                                    </span>
                                @else
                                    {{ number_format($item->product->price, 2) }} ₽
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="changeQuantity({{ $item->id }}, 'decrease')"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                    <span class="mx-2">{{ $item->quantity }}</span>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="changeQuantity({{ $item->id }}, 'increase')"
                                            {{ $item->quantity >= $item->product->qty ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if($item->product->discount > 0)
                                    {{ number_format($item->product->price * (1 - $item->product->discount / 100) * $item->quantity, 2) }} ₽
                                @else
                                    {{ number_format($item->product->price * $item->quantity, 2) }} ₽
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Итого:</strong></td>
                        <td colspan="2">
                            <strong>
                                {{ number_format($items->sum(function($item) {
                                    return $item->product->discount > 0 
                                        ? $item->product->price * (1 - $item->product->discount / 100) * $item->quantity
                                        : $item->product->price * $item->quantity;
                                }), 2) }} ₽
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('create-order') }}" class="btn btn-primary">Оформить заказ</a>
        </div>
    @endif
</div>

@auth
<script>
function changeQuantity(cartItemId, action) {
    fetch(`/cart/${cartItemId}/change-quantity`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action })
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        }
    });
}
</script>
@endauth

@endsection

<style>
.table img {
    max-width: 100px;
    height: auto;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
}

.btn-outline-secondary {
    min-width: 30px;
}

.table-responsive {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
    .table img {
        max-width: 60px;
    }
}
</style>
