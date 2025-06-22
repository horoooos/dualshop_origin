<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'surname' => ['required', 'string', 'max:255'],
            'login' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_.]*$/',
                'min:6',
                'max:32',
                'unique:users,login'
            ],
            'patronymic' => ['nullable', 'string', 'max:32'],
            'terms' => ['required', 'accepted'],
        ], [
            'login.unique' => 'Этот логин уже занят',
            'login.regex' => 'Логин может содержать только латинские буквы, цифры, точку и нижнее подчеркивание',
            'login.min' => 'Логин должен быть не менее 6 символов',
            'login.max' => 'Логин должен быть не более 32 символов',
            'email.unique' => 'Этот email уже зарегистрирован',
            'password.confirmed' => 'Пароли не совпадают',
            'name.required' => 'Укажите имя',
            'surname.required' => 'Укажите фамилию',
            'email.required' => 'Укажите email',
            'email.email' => 'Укажите корректный email',
            'password.required' => 'Укажите пароль',
            'terms.required' => 'Необходимо согласиться с правилами сайта',
            'terms.accepted' => 'Необходимо согласиться с правилами сайта'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'surname' => $request->surname,
            'login' => $request->login,
            'patronymic' => $request->patronymic
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
