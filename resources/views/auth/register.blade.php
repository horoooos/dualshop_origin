@extends('layouts.app')

@section('content')
    <div class="login-page">
        <div class="form-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Имя') }}</label>
                    <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Surname -->
                <div class="form-group">
                    <label for="surname" class="form-label">{{ __('Фамилия') }}</label>
                    <input id="surname" class="form-input" type="text" name="surname" value="{{ old('surname') }}" required autocomplete="surname" />
                    @error('surname')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Patronymic -->
                <div class="form-group">
                    <label for="patronymic" class="form-label">{{ __('Отчество') }}</label>
                    <input id="patronymic" class="form-input" type="text" name="patronymic" value="{{ old('patronymic') }}" required autocomplete="patronymic" />
                    @error('patronymic')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Login -->
                <div class="form-group">
                    <label for="login" class="form-label">{{ __('Логин') }}</label>
                    <input id="login" class="form-input" type="text" name="login" value="{{ old('login') }}" required autocomplete="login" />
                    @error('login')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Пароль') }}</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('Подтвердите пароль') }}</label>
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rules Confirmation -->
                <div class="form-remember">
                    <label for="rules_confirmation" class="form-checkbox-label">
                        <input id="rules_confirmation" class="form-checkbox" type="checkbox" name="rules_confirmation" required />
                        {{ __('Согласие с правилами регистрации') }}
                    </label>
                </div>

                <div class="form-footer">
                    <a class="form-link" href="{{ route('login') }}">
                        {{ __('Уже зарегистрированы?') }}
                    </a>
                    <button type="submit" class="form-button">
                        {{ __('Зарегистрироваться') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
