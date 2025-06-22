@extends('layouts.app')
@section('styles')
<style>
.custom-news-detail {
    max-width: 900px;
    margin: 0 auto;
    font-family: 'Montserrat', sans-serif;
    padding: 20px 15px;
}
.news-detail-card {
    background: #fff;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.news-detail-card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.news-detail-title {
    font-family: 'Roboto', sans-serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: #02111b;
    margin-bottom: 1rem;
}
.news-detail-meta {
    display: flex;
    gap: 1rem;
    color: #3d3d3d;
    font-size: 0.9rem;
    font-family: 'Roboto', sans-serif;
}
.news-detail-image-container {
    margin: 1.5rem 0;
    text-align: center;
    border-radius: 15px;
    overflow: hidden;
}
.news-detail-image {
    max-width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}
.news-detail-content {
    line-height: 1.7;
    color: #3d3d3d;
    font-size: 1.05rem;
    font-family: 'Roboto', sans-serif;
}
.news-detail-content p {
    margin-bottom: 1.2rem;
}
.news-detail-content img {
    max-width: 100%;
    height: auto;
    margin: 1.5rem 0;
    border-radius: 8px;
}
.related-news-title {
    font-family: 'Roboto', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #02111b;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}
.custom-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}
.news-card {
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
    background-color: #fff;
    border: none;
}
.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.news-link {
    color: inherit;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.news-img {
    height: 180px !important;
    width: 100% !important;
    object-fit: cover;
    display: block;
}
.news-title {
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 0.3rem;
    color: #02111b;
}
.news-date {
    font-family: 'Roboto', sans-serif;
    color: #3d3d3d;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}
.news-text {
    font-family: 'Roboto', sans-serif;
    color: #3d3d3d;
    font-size: 0.9rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.news-meta {
    margin-top: auto;
    color: #666;
    font-size: 0.85rem;
    font-family: 'Roboto', sans-serif;
}
.news-icon {
    margin-right: 1rem;
}

.btn-outline-primary {
    border: 1px solid #000;
    color: #000;
    background-color: transparent;
    padding: 0.5rem 1rem;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #000;
    color: #fff;
}

@media (max-width: 767px) {
    .custom-news-detail {
        padding: 1rem;
    }
    .news-detail-card {
        padding: 1.5rem;
    }
    .news-detail-title {
        font-size: 1.5rem;
    }
    .custom-news-grid {
        grid-template-columns: 1fr;
    }
}

.news-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
</style>
@endsection

@section('content')
<div class="container py-4 custom-news-detail">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shops') }}">Новости</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $news->title }}</li>
        </ol>
    </nav>

    <div class="news-detail-card">
        <h1 class="news-detail-title">{{ $news->title }}</h1>
        <div class="news-detail-meta mb-4">
            <span class="news-detail-date">{{ $news->published_at->format('d.m.Y') }}</span>
            <span class="news-detail-views"><i class="bi bi-eye"></i> {{ $news->views }}</span>
        </div>

        @if($news->image)
            <div class="news-detail-image-container mb-4">
                <img src="{{ Vite::asset('resources/media/images/' . $news->image) }}" class="news-detail-image" alt="{{ $news->title }}" width="800" height="450">
            </div>
        @endif

        <div class="news-detail-content">
            {!! $news->content !!}
        </div>

        <div class="news-detail-actions mt-5">
            <a href="{{ route('shops') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Вернуться к списку новостей</a>
        </div>
    </div>

    <div class="related-news mt-5">
        <h3 class="related-news-title mb-4">Другие новости</h3>
        <div class="custom-news-grid">
            @foreach(\App\Models\News::published()->where('id', '!=', $news->id)->newest()->limit(4)->get() as $item)
            <div class="card news-card">
                <a href="{{ route('news.show', $item->id) }}" class="news-link">
                    @if($item->image)
                        <img src="{{ Vite::asset('resources/media/images/' . $item->image) }}" class="news-img" alt="{{ $item->title }}" width="280" height="180">
                    @else
                        <img src="{{ Vite::asset('resources/media/images/novost.svg') }}" class="news-img" alt="News image" width="280" height="180">
                    @endif
                    <div class="card-body">
                        <div class="news-title">{{ $item->title }}</div>
                        <div class="news-date">{{ $item->published_at->format('d.m.Y') }}</div>
                        <div class="news-text">{{ $item->short_description }}</div>
                        <div class="news-meta mt-2">
                            <span class="news-icon"><i class="bi bi-eye"></i> {{ $item->views }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 