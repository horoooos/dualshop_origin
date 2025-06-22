<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Вернуть представление для главной страницы админки
        // Убедитесь, что у вас есть файл resources/views/admin/index.blade.php
        return view('admin.index');
    }

    // Здесь вы можете добавить другие методы для управления продуктами, пользователями и т.д.
} 