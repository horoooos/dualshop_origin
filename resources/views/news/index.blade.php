@extends('layouts.app')
@section('styles')
<style>
.custom-news-page {
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Montserrat', sans-serif;
    padding: 20px 15px;
}

.admin-btn-outline {
    background: black !important; 
    color: white !important; 
    border: 1.5px solid #000 !important;
}

.custom-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
    height: 200px !important;
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

/* Стили для пагинации */
.pagination-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 30px;
    font-family: 'Roboto', sans-serif;
}

.pagination-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.pagination-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: white;
    text-decoration: none;
    color: #000;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.pagination-arrow:hover {
    background-color: #f5f5f5;
}

.pagination-arrow.disabled {
    color: #ccc;
    pointer-events: none;
}

.pagination-numbers {
    display: flex;
    justify-content: center;
    margin: 0 10px;
}

.pagination-number {
    margin: 0 5px;
    font-size: 1rem;
    line-height: 40px;
    text-decoration: none;
    color: #000;
}

.pagination-number.active {
    font-weight: bold;
    color: #000;
}

.pagination-info {
    text-align: center;
    color: #3d3d3d;
    font-size: 0.9rem;
    margin-top: 10px;
    font-family: 'Roboto', sans-serif;
}

.about__title {
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 24px;
    color: #02111b;
    margin-bottom: 1.5rem;
}

.nav-tabs {
    border-bottom: 1px solid #ddd;
}

.nav-tabs .nav-link {
    font-family: 'Roboto', sans-serif;
    color: #3d3d3d;
    background-color: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    border-radius: 0;
    padding: 0.5rem 1rem;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    color: #000;
    border-bottom-color: #ddd;
}

.nav-tabs .nav-link.active {
    color: #000;
    background-color: transparent;
    border-bottom-color: #000;
    font-weight: 600;
}

@media (max-width: 767px) {
    .custom-news-grid {
        grid-template-columns: 1fr;
    }
    
    .nav-tabs .nav-link {
        padding: 0.5rem 0.5rem;
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('content')
<div class="container py-4 custom-news-page">
    <h1 class="about__title mb-4">Новости</h1>
    <ul class="nav nav-tabs mb-4" id="newsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $sortOrder != 'old' ? 'active' : '' }}" href="{{ route('news.index', ['sort' => 'new']) }}">Сначала новые</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $sortOrder == 'old' ? 'active' : '' }}" href="{{ route('news.index', ['sort' => 'old']) }}">Сначала старые</a>
        </li>
    </ul>
    <div class="custom-news-grid">
        @forelse($news as $item)
        <div class="card news-card">
            <a href="{{ route('news.show', $item->id) }}" class="news-link">
                @if($item->image)
                    <img src="{{ Vite::asset('resources/media/images/' . $item->image) }}" class="news-img" alt="{{ $item->title }}" width="300" height="200">
                @else
                    <img src="{{ Vite::asset('resources/media/images/novost.svg') }}" class="news-img" alt="News image" width="300" height="200">
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
        @empty
        <div class="col-12 text-center py-5">
            <p>Новости еще не добавлены</p>
        </div>
        @endforelse
    </div>
    
    @if($news->hasPages())
        <div class="pagination-container">
            <div class="pagination-controls">
                @if($news->onFirstPage())
                    <div class="pagination-arrow disabled">&lt;</div>
                @else
                    <a href="{{ $news->previousPageUrl() }}" class="pagination-arrow">&lt;</a>
                @endif
                
                <div class="pagination-numbers">
                    @for($i = 1; $i <= $news->lastPage(); $i++)
                        @if($i == $news->currentPage())
                            <span class="pagination-number active">{{ $i }}</span>
                        @else
                            <a href="{{ $news->url($i) }}" class="pagination-number">{{ $i }}</a>
                        @endif
                    @endfor
                </div>
                
                @if($news->hasMorePages())
                    <a href="{{ $news->nextPageUrl() }}" class="pagination-arrow">&gt;</a>
                @else
                    <div class="pagination-arrow disabled">&gt;</div>
                @endif
            </div>
        
        </div>
    @endif
</div>
@endsection 