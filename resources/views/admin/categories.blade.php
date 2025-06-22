@extends('layouts.admin')
@section('content')
<div class="admin-content">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Список категорий</h1>
    <a href="{{ route('admin.categories.create') }}" class="admin-btn mb-3">Добавить новую категорию</a>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="admin-btn btn-sm">Редактировать</a>
                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="post" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn btn-sm" style="background:#dc3545;" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
