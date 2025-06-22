@extends('layouts.admin')
@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem;">Добавление новости</h1>
        <a href="{{ route('admin.news') }}" class="admin-btn admin-btn-outline mb-3">
            <i class="bi bi-arrow-left"></i> Назад к списку
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="short_description" class="form-label">Краткое описание <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3" required>{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Полный текст новости <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    <div class="form-text">Рекомендуемый размер: 800x400 пикселей. Максимальный размер: 2MB.</div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Опубликовать сразу</label>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="admin-btn">
                        <i class="bi bi-check-lg"></i> Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('#content')) {
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
@endsection 