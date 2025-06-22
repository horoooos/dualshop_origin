@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Редактировать товар</h1>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="title" class="form-label">Название товара</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ $product->price }}" required>
        </div>
        <div class="mb-3">
            <label for="old_price" class="form-label">Старая цена (для скидки)</label>
            <input type="number" class="form-control" id="old_price" name="old_price" step="0.01" value="{{ $product->old_price ?? '' }}">
        </div>
        <div class="mb-3">
            <label for="qty" class="form-label">Количество</label>
            <input type="number" class="form-control" id="qty" name="qty" value="{{ $product->qty }}" required>
        </div>
        {{-- Поле для рейтинга --}}
        <div class="mb-3">
            <label for="rating" class="form-label">Рейтинг</label>
            <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="5" value="{{ $product->rating ?? 0 }}">
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Категория</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Цвет</label>
            <input type="text" class="form-control" id="color" name="color" value="{{ $product->color ?? '' }}">
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Страна</label>
            <input type="text" class="form-control" id="country" name="country" value="{{ $product->country ?? '' }}">
        </div>

        {{-- Секция для управления изображениями --}}
        <div class="mb-3">
            <label for="images" class="form-label">Изображения товара</label>
            {{-- Отображение текущих изображений --}}
            <div id="current-images" class="d-flex flex-wrap gap-2 mb-3">
                @forelse($product->images as $image)
                    <div class="card" style="width: 150px;">
                        @php
                            $imagePath = 'media/images/' . $image->image_path;
                            $imageUrl = asset($imagePath);
                        @endphp
                        {{-- Временный отладочный вывод путей --}}
                        {{-- <p style="font-size: 0.7em; color: blue; word-break: break-all;">DB Path: {{ $image->image_path }}</p> --}}
                        {{-- <p style="font-size: 0.7em; color: green; word-break: break-all;">Vite URL: {{ $imageUrl }}</p> --}}

                        <img src="{{ $imageUrl }}" class="card-img-top" alt="Product Image" style="height: 100px; object-fit: cover;">
                        <div class="card-body p-2">
                            <button type="button" class="btn btn-danger btn-sm delete-image" data-image-id="{{ $image->id }}">Удалить</button>
                            {{-- Кнопка Изменить и скрытый инпут --}}
                            <button type="button" class="btn btn-secondary btn-sm change-image-btn mt-1" data-image-id="{{ $image->id }}">Изменить</button>
                            <input type="file" class="d-none change-image-input" data-image-id="{{ $image->id }}" name="existing_images[{{ $image->id }}]" accept="image/*">
                        </div>
                    </div>
                @empty
                    <p>Нет загруженных изображений.</p>
                @endforelse
            </div>

            {{-- Контейнер для предварительного просмотра новых изображений --}}
            <div id="new-images-preview" class="d-flex flex-wrap gap-2 mb-3">
                {{-- Предварительный просмотр новых изображений будет здесь --}}
            </div>

            {{-- Поле для загрузки новых изображений --}}
            <input type="file" class="form-control" id="new_images" name="new_images[]" accept="image/*" multiple>
            <small class="form-text text-muted">Выберите один или несколько файлов для загрузки.</small>
        </div>

        {{-- Hidden field for images to delete --}}
        <div id="images-to-delete"></div>

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $product->description }}</textarea>
        </div>
        
        {{-- Явные статусы для отображения на сайте --}}
        <div class="mb-3">
            <label class="form-label">Статусы товара на сайте</label>
            <div class="form-check">
                {{-- Используем is_bestseller для соответствия публичной части --}}
                <input type="checkbox" class="form-check-input" id="is_bestseller" name="is_bestseller" value="1" {{ ($product->is_bestseller ?? 0) == 1 ? 'checked' : '' }}> {{-- Предполагается поле is_bestseller в БД --}}
                <label class="form-check-label" for="is_bestseller">Хит продаж</label>
            </div>
             <div class="form-check">
                {{-- Добавляем чекбокс для Новинки --}}
                <input type="checkbox" class="form-check-input" id="is_new" name="is_new" value="1" {{ ($product->is_new ?? 0) == 1 ? 'checked' : '' }}> {{-- Предполагается поле is_new в БД --}}
                <label class="form-check-label" for="is_new">Новинка</label>
            </div>
            {{-- Статус "Скидка" управляется полем "Скидка (%)" --}}
            {{-- Статус "В наличии" управляется полем "Количество" --}}
        </div>

        {{-- Секция для спецификаций --}}
        <h2 class="mt-4">Спецификации</h2>
        <div id="specifications-container">
            {{-- Существующие спецификации будут загружены сюда --}}
            @if(isset($product->specifications))
                @foreach($product->specifications as $index => $spec)
                    <div class="row mb-3 specification-row">
                         <input type="hidden" name="specifications[{{ $index }}][id]" value="{{ $spec->id }}">
                        <div class="col-md-4">
                            <input type="text" name="specifications[{{ $index }}][spec_name]" class="form-control" placeholder="Название спецификации" value="{{ $spec->spec_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="specifications[{{ $index }}][spec_value]" class="form-control" placeholder="Значение спецификации" value="{{ $spec->spec_value }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="specifications[{{ $index }}][group]" class="form-control" placeholder="Группа (напр. Основные)" value="{{ $spec->group }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox" name="specifications[{{ $index }}][is_filterable]" class="form-check-input" value="1" {{ $spec->is_filterable ? 'checked' : '' }}>
                                <label class="form-check-label">Фильтр</label>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-specification">Удалить</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="admin-btn btn-sm admin-btn-outline mt-2" id="add-specification">Добавить спецификацию</button>

        <button type="submit" class="admin-btn mt-4">Сохранить изменения</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    console.log('Скрипт редактирования товара загружен.');

    // Логика добавления/удаления спецификаций
    let specificationIndex = 0; // Инициализируем счетчик

    // Функция для обновления индексов спецификаций после удаления
    function updateSpecificationIndexes() {
        document.querySelectorAll('#specifications-container .specification-row').forEach((row, newIndex) => {
            row.querySelectorAll('input, select, textarea').forEach(input => {
                 // Пропускаем скрытое поле id
                if (input.name.includes('[id]')) {
                    input.name = `specifications[${newIndex}][id]`;
                } else {
                    input.name = input.name.replace(/specifications\[\d+\]/, `specifications[${newIndex}]`);
                }
            });
        });
         // Обновляем счетчик после пересчета
         specificationIndex = document.querySelectorAll('#specifications-container .specification-row').length;
         console.log('Specification indexes updated. Current index: ', specificationIndex);
    }

    document.getElementById('add-specification').addEventListener('click', function () {
        console.log('Нажата кнопка "Добавить спецификацию"');
        const container = document.getElementById('specifications-container');
        const index = specificationIndex++; // Используем и увеличиваем новый счетчик

        const specHtml = `
            <div class="row mb-3 specification-row">
                {{-- Добавляем скрытое поле для новых спецификаций, id будет null --}}
                <input type="hidden" name="specifications[${index}][id]" value="">
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
        // После добавления новой строки, пересчитывать индексы не нужно, так как используется уникальный счетчик
    });

    document.getElementById('specifications-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-specification')) {
            const row = e.target.closest('.specification-row');
            // Если спецификация имеет ID, помечаем ее для удаления вместо удаления строки сразу
            const specIdInput = row.querySelector('input[name$="[id]"]');
            if (specIdInput && specIdInput.value) {
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'specifications_to_delete[]';
                deleteInput.value = specIdInput.value;
                document.querySelector('form').appendChild(deleteInput);
            }
            row.remove();

            // Пересчитываем индексы после удаления для корректной отправки формы
            updateSpecificationIndexes();
        }
    });

    // При загрузке страницы инициализируем счетчик и пересчитываем индексы существующих спецификаций
     document.addEventListener('DOMContentLoaded', function() {
         updateSpecificationIndexes(); // Пересчитываем индексы при загрузке
    });

    // Логика для удаления изображений
    document.getElementById('current-images').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-image')) {
            const imageCard = e.target.closest('.card');
            const imageId = e.target.dataset.imageId;

            if (confirm('Вы уверены, что хотите удалить это изображение?')) {
                // Добавляем ID изображения в скрытое поле для удаления
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'images_to_delete[]';
                deleteInput.value = imageId;
                document.getElementById('images-to-delete').appendChild(deleteInput);

                // Визуально удаляем карточку изображения
                imageCard.remove();
            }
        }

        // Обработка клика по кнопке Изменить
        
        if (e.target.classList.contains('change-image-btn')) {
            const imageId = e.target.dataset.imageId;
            const fileInput = document.querySelector(`.change-image-input[data-image-id="${imageId}"]`);
            if (fileInput) {
                fileInput.click(); // Имитируем клик по скрытому файловому инпуту
            }
        }
        
    });

    // Логика для предварительного просмотра новых изображений
    const newImagesInput = document.getElementById('new_images');
    const newImagesPreviewContainer = document.getElementById('new-images-preview');

    newImagesInput.addEventListener('change', function(event) {
        // Очищаем предыдущий предварительный просмотр
        newImagesPreviewContainer.innerHTML = '';

        const files = event.target.files;

        if (files) {
            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imgDiv = document.createElement('div');
                        imgDiv.classList.add('card');
                        imgDiv.style.width = '150px';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('card-img-top');
                        img.alt = 'New Product Image Preview';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';

                        const cardBody = document.createElement('div');
                        cardBody.classList.add('card-body', 'p-2', 'text-center');
                        cardBody.textContent = file.name; // Отобразим имя файла

                        imgDiv.appendChild(img);
                        imgDiv.appendChild(cardBody);
                        newImagesPreviewContainer.appendChild(imgDiv);
                    }

                    reader.readAsDataURL(file);
                } else {
                    // Optionally, provide feedback for non-image files
                    console.log('Selected file is not an image:', file.name);
                }
            }
        }
    });

</script>
@endpush
