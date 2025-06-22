@extends('layouts.admin')
@section('content')
<div class="admin-content">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Список товаров</h1>
    {{-- {{ dd($products) }} --}} {{-- Временный отладочный вывод --}}
    {{-- {{ dd($products) }} --}}
    <table class="admin-table">
        <thead>
            <tr>
                <th>Изображения</th>
                <th>Названия</th>
                <th>Категория</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        @php
                            $imageFileName = null;
                            $debugImagePath = null; // Для отладочного вывода
                            // Проверяем отношение images first
                            if ($product->images && $product->images->count() > 0) {
                                $imageFileName = $product->images->first()->image_path;
                                $debugImagePath = $imageFileName ? 'images relationship: ' . $imageFileName : 'images relationship: (empty path)';
                            }
                            // Fallback to img field
                            elseif (!empty($product->img)) {
                                $imageFileName = $product->img;
                                 $debugImagePath = $imageFileName ? 'img field: ' . $imageFileName : 'img field: (empty path)';
                            }

                            $imageUrl = null;
                            if ($imageFileName) {
                                 // Используем Vite::asset для формирования URL
                                 $imageUrl = Vite::asset('resources/media/images/' . $imageFileName);
                            }
                        @endphp

                        {{-- @if($debugImagePath) --}}
                            {{-- Временный отладочный вывод пути к изображению --}}
                            {{-- <p style="font-size: 0.8em; color: gray;">{{ $debugImagePath }}</p> --}}
                        {{-- @endif --}}

                        @if($imageUrl)
                            <img src="{{ asset('media/images/' . $imageFileName) }}" alt="{{ $product->title }}" width="100px">
                        @else
                            {{-- Заглушка, если изображения нет --}}
                            <div style="width: 100px; height: 100px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                                Нет фото
                            </div>
                        @endif
                    </td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->category_name }}</td>
                    <td>{{ $product->qty }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="admin-btn btn-sm">Редактировать</a>
                        <form action="{{ route('admin.products.delete', ['id' => $product->id]) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn btn-sm" style="background:#dc3545;" onclick="return confirm('Вы уверены?');">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
