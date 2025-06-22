<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Здесь вам нужно добавить вашу логику для проверки, является ли пользователь администратором.
        // Например, если у вашей модели User есть поле `is_admin` (булево):
        // if (Auth::check() && Auth::user()->is_admin) {
        //     return $next($request);
        // }

        // Пока что для примера я просто перенаправлю не-админов на главную страницу.
        // ЗАМЕНИТЕ ЭТУ ЛОГИКУ В СООТВЕТСТВИИ С ВАШИМ СПОСОБОМ ОПРЕДЕЛЕНИЯ АДМИНИСТРАТОРА И ЖЕЛАЕМЫМ ПОВЕДЕНИЕМ!
        if (Auth::check()) {
             // Здесь должна быть ваша реальная проверка на админа
             if (Auth::user()->is_admin) { // Пример
                 return $next($request);
             }
             // Если проверка не пройдена:
             abort(403, 'Unauthorized action.'); // Например, возвращаем 403 ошибку
        }

        // Если пользователь не аутентифицирован, перенаправляем на страницу входа
        return redirect()->route('login');
    }
} 