@extends('layouts.admin')

@section('content')
<div class="admin-content">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Добавить новый товар</h1>
    <form action="{{ url('/admin/product-create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="title" class="form-label">Название товара</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Цена</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price') }}" required>
                </div>
                {{-- Поле для старой цены (для скидки) --}}
                <div class="mb-3">
                    <label for="old_price" class="form-label">Старая цена (для скидки)</label>
                    <input type="number" class="form-control" id="old_price" name="old_price" step="0.01" value="{{ old('old_price') }}">
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label">Количество</label>
                    <input type="number" class="form-control" id="qty" name="qty" value="{{ old('qty') }}" required>
                </div>
                {{-- Поле для рейтинга --}}
                <div class="mb-3">
                    <label for="rating" class="form-label">Рейтинг</label>
                    <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', 0) }}">
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Страна</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}">
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Цвет</label>
                    <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="new_images" class="form-label">Изображения товара</label>
                    {{-- Контейнер для предварительного просмотра новых изображений --}}
                    <div id="new-images-preview" class="d-flex flex-wrap gap-2 mb-3">
                        {{-- Предварительный просмотр новых изображений будет здесь --}}
                    </div>
                    {{-- Поле для загрузки новых изображений --}}
                    <input type="file" class="form-control" id="new_images" name="new_images[]" accept="image/*" multiple>
                    <small class="form-text text-muted">Выберите один или несколько файлов для загрузки.</small>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                </div>
                {{-- Явные статусы для отображения на сайте --}}
                <div class="mb-3">
                    <label class="form-label">Статусы товара на сайте</label>
                    <div class="form-check">
                        {{-- Чекбокс для Хита продаж --}}
                        <input type="checkbox" class="form-check-input" id="is_bestseller" name="is_bestseller" value="1" {{ old('is_bestseller') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_bestseller">Хит продаж</label>
                    </div>
                     <div class="form-check">
                        {{-- Чекбокс для Новинки --}}
                        <input type="checkbox" class="form-check-input" id="is_new" name="is_new" value="1" {{ old('is_new', true) ? 'checked' : '' }}> {{-- По умолчанию Новый --}}
                        <label class="form-check-label" for="is_new">Новинка</label>
                    </div>
                     {{-- Статус "Скидка" управляется полем "Старая цена" --}}
                     {{-- Статус "В наличии" управляется полем "Количество" --}}
                </div>
            </div>
        </div>
        <hr>
        <h2 class="mt-4" style="font-size:1.3rem;">Спецификации</h2>
        <div id="specifications-container"></div>
        <button type="button" class="admin-btn btn-sm admin-btn-outline mt-2" id="add-specification">Добавить спецификацию</button>
        <button type="submit" class="admin-btn mt-4">Добавить товар</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    console.log('Скрипт создания товара загружен.');

    // Логика добавления/удаления спецификаций
    let specificationIndex = 0; // Инициализируем счетчик

    document.getElementById('add-specification').addEventListener('click', function () {
        console.log('Нажата кнопка "Добавить спецификацию"');
        const container = document.getElementById('specifications-container');
        const index = specificationIndex++; // Используем и увеличиваем новый счетчик

        const specHtml = `
            <div class="row mb-3 specification-row">
                <div class="col-md-4">
                    <input type="text" name="specifications[${index}][spec_name]" class="form-control" placeholder="Название спецификации" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="specifications[${index}][spec_value]" class="form-control" placeholder="Значение спецификации" required>
                </div>
                 <div class="col-md-2">
                     <input type="text" name="specifications[${index}][group]" class="form-control" placeholder="Группа (напр. Основные)">
                </div>
                 <div class="col-md-1 d-flex align-items-center">
                    <div class="form-check">
                        <input type="checkbox" name="specifications[${index}][is_filterable]" class="form-check-input" value="1">
                        <label class="form-check-label">Фильтр</label>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-specification">Удалить</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', specHtml);
    });

    document.getElementById('specifications-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-specification')) {
            e.target.closest('.specification-row').remove();
            // При создании не нужно пересчитывать индексы, так как используется уникальный счетчик
        }
    });

    // Логика предварительного просмотра новых изображений
    document.getElementById('new_images').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('new-images-preview');
        previewContainer.innerHTML = ''; // Очищаем предыдущие превью

        const files = event.target.files;
        if (files) {
            for (const file of files) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgHtml = `
                        <div class="card" style="width: 100px;">
                            <img src="${e.target.result}" class="card-img-top" alt="Image Preview" style="height: 80px; object-fit: cover;">
                            <div class="card-body p-1 text-center">
                                <small>${file.name}</small>
                            </div>
                        </div>
                    `;
                    previewContainer.insertAdjacentHTML('beforeend', imgHtml);
                }
                reader.readAsDataURL(file);
            }
        }
    });

    // Инициализация индексов для спецификаций при загрузке, если вдруг уже есть старые данные
     document.addEventListener('DOMContentLoaded', function() {
        // На странице создания спецификаций по умолчанию нет, так что инициализируем с 0
         specificationIndex = 0;
         console.log('Specification index initialized: ', specificationIndex);
    });

</script>
@endpush
