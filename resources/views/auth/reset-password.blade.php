<x-guest-layout>
    {{-- Обертываем форму в контейнер-карточку --}}
    <div class="auth-form-card">
        <h2 class="form-title text-center mb-4">Сброс пароля</h2>
        <form method="POST" action="{{ route('password.store') }}" class="form-container form-reset-password" id="reset-password-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Пароль')" />
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />

                <x-text-input id="password_confirmation" class="form-control"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-grid gap-2">
                <x-primary-button class="btn btn-primary">
                    {{ __('Сбросить пароль') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <style>
        /* Стили для карточки формы аутентификации */
        .auth-form-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            max-width: 400px; /* Ограничиваем ширину для центрирования */
            margin: 50px auto; /* Центрируем по горизонтали с отступом сверху */
        }

        .form-title {
             font-size: 1.5rem;
             font-weight: 600;
             margin-bottom: 1.5rem; /* Отступ после заголовка */
         }

        /* Переопределяем стили компонентов Blade, если нужно */
        /* Пример: */
        .auth-form-card .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
        }

        .auth-form-card .btn-primary {
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 1.1rem;
        }

         .auth-form-card label {
             font-weight: 500;
             margin-bottom: 0.5rem;
         }

         .auth-form-card .mt-2 {
             margin-top: 0.5rem !important;
         }

    </style>

</x-guest-layout>
