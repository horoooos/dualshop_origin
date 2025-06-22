@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif
                </div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
            </div>

            <div class="profile-info">
                <div class="info-item">
                    <span class="info-label">Телефон:</span>
                    <span class="info-value">{{ $user->phone ?? 'Не указан' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Дата регистрации:</span>
                    <span class="info-value">{{ $user->created_at->format('d.m.Y') }}</span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    Редактировать профиль
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    max-width: 600px;
    margin: 0 auto;
}

.profile-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    overflow: hidden;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-avatar i {
    font-size: 5rem;
    color: #666;
}

.profile-name {
    font-family: 'Roboto', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #02111b;
    margin-bottom: 0.5rem;
}

.profile-email {
    color: #666;
    font-size: 1rem;
}

.profile-info {
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-size: 0.875rem;
}

.info-value {
    color: #02111b;
    font-weight: 500;
}

.profile-actions {
    text-align: center;
}

.btn-primary {
    background: #000;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary:hover {
    background: #333;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .profile-card {
        padding: 1.5rem;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
    }
    
    .profile-name {
        font-size: 1.25rem;
    }
}
</style>
@endsection 