@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/profile-edit.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4">
    <div class="profile-edit-container">
        <!-- Форма обновления профиля -->
        <div class="profile-form-card">
            <h2 class="form-title">Редактирование профиля</h2>
            <form method="post" action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <!-- Avatar -->
                <div class="form-group">
                    <label class="form-label">Аватар</label>
                    <div class="avatar-upload">
                        <div class="avatar-preview">
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Avatar" id="avatarPreview">
                            @else
                                <div class="profile-avatar1">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            @endif
                        </div>
                        <div class="avatar-edit">
                            <label for="avatar">Выбрать аватар</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*">
                            <small class="form-text">Рекомендуемый размер: 200x200 пикселей</small>
                        </div>
                    </div>
                    @error('avatar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Имя -->
                <div class="form-group">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Телефон -->
                <div class="form-group">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        Сохранить изменения
                    </button>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success">
                        Профиль успешно обновлен
                    </div>
                @endif
            </form>
        </div>

        <!-- Форма изменения пароля -->
        <div class="profile-form-card">
            <h2 class="form-title">Изменение пароля</h2>
            <form method="post" action="{{ route('password.update') }}" class="profile-form">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password" class="form-label">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" class="form-input" required>
                    @error('current_password', 'updatePassword')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Новый пароль</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    @error('password', 'updatePassword')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    @error('password_confirmation', 'updatePassword')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        Изменить пароль
                    </button>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success">
                        Пароль успешно изменен
                    </div>
                @endif
            </form>
        </div>

        <!-- Удаление аккаунта -->
        <div class="profile-form-card danger-zone">
            <h2 class="form-title">Удаление аккаунта</h2>
            <p class="danger-text">После удаления вашего аккаунта все его ресурсы и данные будут безвозвратно удалены.</p>
            
            <form method="post" action="{{ route('profile.destroy') }}" class="profile-form">
                @csrf
                @method('delete')

                <div class="form-group">
                    <label for="delete_password" class="form-label">Пароль</label>
                    <input type="password" id="delete_password" name="password" class="form-input" required>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-danger" onclick="return confirm('Вы уверены, что хотите удалить свой аккаунт?')">
                        Удалить аккаунт
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('avatar').onchange = function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarPreview) {
                avatarPreview.src = e.target.result;
            } else {
                const profileAvatar = document.querySelector('.profile-avatar1');
                if (profileAvatar) {
                    profileAvatar.innerHTML = `<img src="${e.target.result}" alt="Avatar" id="avatarPreview">`;
                }
            }
            
            // Обновляем аватарку на странице профиля
            const profileAvatar = document.querySelector('.profile-avatar');
            if (profileAvatar) {
                profileAvatar.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="avatar-img">`;
            }
        }
        reader.readAsDataURL(file);
    }
}

// Добавляем обработчик успешной отправки формы
document.querySelector('form[enctype="multipart/form-data"]').addEventListener('submit', function(e) {
    // После успешной отправки формы
    setTimeout(function() {
        // Обновляем страницу профиля
        window.location.href = '{{ route("profile.index") }}';
    }, 1000); // Ждем 1 секунду перед обновлением
});
</script>
@endsection
