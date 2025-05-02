@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Добавить новый товар</h1>
    <form action="/product-create" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Название товара</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">Скидка (%)</label>
            <input type="number" class="form-control" id="discount" name="discount" step="0.01" value="0">
        </div>
        <div class="mb-3">
            <label for="qty" class="form-label">Количество</label>
            <input type="number" class="form-control" id="qty" name="qty" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Категория</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="img" class="form-label">Изображение</label>
            <input type="file" class="form-control" id="img" name="img" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_seasonal" name="is_seasonal" value="1">
                <label class="form-check-label" for="is_seasonal">Сезонный товар</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" value="1">
                <label class="form-check-label" for="is_popular">Популярный товар</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_new" name="is_new" value="1" checked>
                <label class="form-check-label" for="is_new">Новый товар</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Добавить товар</button>
    </form>
</div>
@endsection
