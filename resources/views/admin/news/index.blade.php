@extends('layouts.admin')
@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem;">Управление новостями</h1>
        <a href="{{ route('admin.news.create') }}" class="admin-btn">
            <i class="bi bi-plus-circle"></i> Добавить новость
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Заголовок</th>
                            <th>Дата публикации</th>
                            <th>Статус</th>
                            <th>Просмотры</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('media/images/' . $item->image) }}" alt="{{ $item->title }}" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">Нет изображения</span>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->published_at ? $item->published_at->format('d.m.Y H:i') : 'Не опубликована' }}</td>
                                <td>
                                    @if($item->is_published)
                                        <span class="badge bg-success">Опубликована</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Черновик</span>
                                    @endif
                                </td>
                                <td>{{ $item->views }}</td>
                                <td>
                                    <div class="admin-btn-group">
                                        <a href="{{ route('news.show', $item->id) }}" class="admin-btn admin-btn-outline" target="_blank" title="Просмотр">👁</a>
                                        <a href="{{ route('admin.news.edit', $item->id) }}" class="admin-btn" title="Редактировать">✏️</a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger" title="Удалить">🗑</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Новости не найдены</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 