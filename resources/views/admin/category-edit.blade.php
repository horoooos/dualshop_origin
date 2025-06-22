@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h1 style="font-family: 'Yeseva One' !important, serif; font-size: 2rem; margin-bottom: 1.5rem;">Редактировать категорию: {{ $category->name }}</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">Название категории</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Родительская категория</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">Нет (корневая категория)</option>
                @foreach($categories as $cat)
                    @if($cat->id !== $category->id)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <button type="submit" class="admin-btn">Сохранить изменения</button>
    </form>
</div>
@endsection
