@extends('layouts.admin')
@section('content')
<div class="admin-content">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Список заказов</h1>
    <div class="mb-3">
        <a href="{{ route('admin.orders', ['filter' => 'new']) }}" class="admin-btn admin-btn-outline {{ request('filter') == 'new' ? 'active' : '' }}">Новые</a>
        <a href="{{ route('admin.orders', ['filter' => 'confirmed']) }}" class="admin-btn admin-btn-outline {{ request('filter') == 'confirmed' ? 'active' : '' }}">Подтвержденные</a>
        <a href="{{ route('admin.orders', ['filter' => 'canceled']) }}" class="admin-btn admin-btn-outline {{ request('filter') == 'canceled' ? 'active' : '' }}">Отмененные</a>
        <a href="{{ route('admin.orders') }}" class="admin-btn admin-btn-outline {{ !request('filter') ? 'active' : '' }}">Показать все</a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Номер заказа</th>
                <th>ФИО клиента</th>
                <th>Товары в заказе</th>
                <th>Дата создания</th>
                <th>Итог сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->name }}</td>
                    <td>
                        @foreach($order->products as $product)
                            <div>
                                {{ $product->title }} x{{ $product->qty }}: {{ number_format($product->price * $product->qty, 2, ',', ' ') }} ₽
                            </div>
                        @endforeach
                        <div class="fw-bold mt-1">Всего товаров: {{ $order->totalQty }}</div>
                    </td>
                    <td>{{ $order->date->format('d.m.Y H:i') }}</td>
                    <td>{{ number_format($order->totalPrice, 2, ',', ' ') }} ₽</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        @if($order->status == 'Новый')
                            <form action="{{ route('admin.orders.updateStatus', ['action' => 'confirm', 'number' => $order->number]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="admin-btn btn-sm">Подтвердить</button>
                            </form>
                            <form action="{{ route('admin.orders.updateStatus', ['action' => 'cancel', 'number' => $order->number]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="admin-btn btn-sm admin-btn-outline">Отменить</button>
                            </form>
                        @endif
                        @if($order->status == 'Новый')
                            <form action="{{ route('admin.orders.delete', $order->number) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn btn-sm admin-btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">Удалить</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
