@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-form">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label">{{ __('Имя') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Surname -->
                    <div class="mb-4">
                        <label for="surname" class="form-label">{{ __('Фамилия') }}</label>
                        <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" 
                               name="surname" value="{{ old('surname') }}" required>
                        @error('surname')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Patronymic -->
                    <div class="mb-4">
                        <label for="patronymic" class="form-label">{{ __('Отчество') }}</label>
                        <input id="patronymic" type="text" class="form-control @error('patronymic') is-invalid @enderror" 
                               name="patronymic" value="{{ old('patronymic') }}">
                        @error('patronymic')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div class="form-text">Необязательное поле</div>
                    </div>

                    <!-- Login -->
                    <div class="mb-4">
                        <label for="login" class="form-label">{{ __('Логин') }}</label>
                        <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" 
                               name="login" value="{{ old('login') }}" required>
                        @error('login')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div class="form-text">Минимум 6 символов, только латинские буквы, цифры, точка и нижнее подчеркивание</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">{{ __('Пароль') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">{{ __('Подтверждение пароля') }}</label>
                        <input id="password_confirmation" type="password" class="form-control" 
                               name="password_confirmation" required>
                    </div>

                    <!-- Terms Acceptance -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Я согласен с <a href="{{ route('terms') }}" class="terms-link">правилами сайта</a>
                            </label>
                            @error('terms')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <a href="{{ route('login') }}" class="auth-link">
                                {{ __('Уже есть аккаунт?') }}
                            </a>
                        </div>
                        <button type="submit" class="btn-auth-small">
                            {{ __('Зарегистрироваться') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.auth-form {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.form-label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control {
    height: 48px;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #000;
    box-shadow: none;
}

.form-text {
    color: #6B7280;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-check-input {
    width: 1.1em;
    height: 1.1em;
    margin-top: 0.25em;
    border-color: #D1D5DB;
}

.form-check-input:checked {
    background-color: #000;
    border-color: #000;
}

.form-check-label {
    color: #4B5563;
    font-size: 0.95rem;
}

.terms-link {
    color: #000;
    text-decoration: underline;
    transition: color 0.2s;
}

.terms-link:hover {
    color: #4B5563;
}

.btn-auth-small {
    height: 48px;
    background: #000;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.2s;
    padding: 0 2rem;
}

.btn-auth-small:hover {
    background: #1a1a1a;
    color: white;
}

.auth-link {
    color: #4B5563;
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.2s;
}

.auth-link:hover {
    color: #000;
}

.invalid-feedback {
    font-size: 0.875rem;
    color: #DC2626;
}

@media (max-width: 768px) {
    .auth-form {
        padding: 1.5rem;
    }
    
    .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-auth-small {
        width: 100%;
    }
}
</style>
@endsection
