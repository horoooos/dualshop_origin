@extends('layouts.admin')

@section('content')
<div class="admin-content" style="max-width: 1100px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 2.5rem 2.5rem 2rem 2.5rem; margin-bottom: 2rem;">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2.5rem; margin-bottom: 0.5rem;">Добро пожаловать в админ-панель</h1>
        <p style="font-size: 1.15rem; color: #444; margin-bottom: 2.5rem;">Управляйте всеми разделами магазина в современном интерфейсе <b>DualShop</b>.</p>
        <div class="row g-4">
            <div class="col-md-4 col-12 mb-4">
                <a href="{{ route('admin.products') }}" class="admin-dashboard-card">
                    🛒<span>Товары</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="admin-dashboard-card">
                    ➕<span>Добавить товар</span>
                </a>
            </div>
            <div class="col-md-4 col-12 mb-4">
                <a href="{{ route('admin.categories') }}" class="admin-dashboard-card">
                    🗂<span>Категории</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="admin-dashboard-card">
                    ➕<span>Добавить категорию</span>
                </a>
            </div>
            <div class="col-md-4 col-12 mb-4">
                <a href="{{ route('admin.orders') }}" class="admin-dashboard-card">
                    📦<span>Заказы</span>
                </a>
            </div>
            <div class="col-md-4 col-12 mb-4">
                <a href="{{ route('admin.news') }}" class="admin-dashboard-card">
                    📰<span>Новости</span>
                </a>
                <a href="{{ route('admin.news.create') }}" class="admin-dashboard-card">
                    ➕<span>Добавить новость</span>
                </a>
            </div>
        </div>
    </div>
</div>
<style>
.admin-dashboard-card {
    display: flex;
    align-items: center;
    gap: 0.7em;
    background: #fff;
    border: 2px solid #111;
    border-radius: 10px;
    font-size: 1.15rem;
    font-weight: 500;
    color: #111;
    padding: 1.1em 1.3em;
    margin-bottom: 1.1em;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.18s;
    text-decoration: none;
}
.admin-dashboard-card:hover {
    background: #111;
    color: #fff;
    border-color: #111;
    transform: translateY(-2px) scale(1.03);
}
.admin-dashboard-card span {
    font-size: 1.08em;
    font-weight: 500;
}
@media (max-width: 767px) {
    .admin-dashboard-card {
        font-size: 1rem;
        padding: 1em 1em;
    }
}
</style>
@endsection 