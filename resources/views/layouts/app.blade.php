<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="">
        <div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    <footer class="dualshop-footer">
    <div class="container">
      <!-- Основной заголовок -->
      <div class="footer-header text-center mb-5">
        <h2 class="footer-title">DualShop - Ваш партнер в мире электроники</h2>
        <p class="footer-subtitle">
          В DualShop мы не просто магазин, мы - ваш партнер в мире электроники. Наша разнообразная и ответственная команда берет на себя полную ответственность за ваш опыт покупок, делая возможным безупречное выполнение вашего плана.
        </p>
      </div>

      <div class="footer-actions-wrapper">
        <div class="footer-actions">
          <button class="btn btn-light footer-btn">
            <span class="btn-emoji">👋</span> Связаться
          </button>
          <button class="btn btn-light footer-btn">
            <span class="btn-emoji">🔍</span> Отзывы
          </button>
        </div>
      </div>
  
      <!-- Разделитель -->
      <div class="footer-separator"></div>
  
      <!-- Нижняя часть футера -->
      <div class="footer-bottom">
        <div class="row align-items-center">
          <!-- Логотип и копирайт -->
          <div class="col-md-6 text-center text-md-start">
            <div class="footer-logo">dualshop.</div>
            <div class="footer-copyright">© 2025 DualShop. Все права защищены.</div>
          </div>
  
          <!-- Социальные иконки -->
          <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
            <div class="social-icons">
              <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
              <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
              <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</html>
