@extends('layouts.admin')
@section('content')
    <div class="admin-content">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Создать новую категорию</h1>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Название категории</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">Родительская категория</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="">Нет (корневая категория)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="admin-btn">Создать категорию</button>
        </form>
    </div>
@endsection
