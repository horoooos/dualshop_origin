<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Yeseva+One&display=swap" rel="stylesheet">
        
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
        
        <!-- Vite Assets -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <!-- Custom Styles -->
        <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
        
        <?php echo $__env->yieldContent('styles'); ?>

        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        
        
        <?php if(Request::routeIs('welcome') || Request::routeIs('catalog') || Request::routeIs('catalog.category')): ?>
            <script src="<?php echo e(asset('js/catalog-menu.js')); ?>"></script>
        <?php endif; ?>

        
        <?php echo $__env->yieldPushContent('scripts'); ?>

        
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

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
                    
                    <a href="<?php echo e(route('delivery')); ?>" class="btn btn-light footer-btn">
                        <span class="btn-emoji">👋</span> Доставка
                    </a>
                    
                    <a href="<?php echo e(route('shops')); ?>" class="btn btn-light footer-btn">
                         <span class="btn-emoji">🔍</span> Новости
                     </a>
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
                            <a href="https://rutube.ru/channel/45642184/" class="social-icon social-icon-bw" title="Rutube" target="_blank" rel="noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="28" height="28" fill="none">
                                    <rect x="1" y="1" width="30" height="30" rx="7" fill="#fff"/>
                                    <rect x="4" y="4" width="24" height="24" rx="6" fill="#222"/>
                                    <path d="M13.5 10C11.0147 10 9 12.0147 9 14.5V17.5C9 19.9853 11.0147 22 13.5 22H18.5C20.9853 22 23 19.9853 23 17.5V14.5C23 12.0147 20.9853 10 18.5 10H13.5ZM13.5 12H18.5C19.8807 12 21 13.1193 21 14.5V17.5C21 18.8807 19.8807 20 18.5 20H13.5C12.1193 20 11 18.8807 11 17.5V14.5C11 13.1193 12.1193 12 13.5 12Z" fill="#fff"/>
                                </svg>
                            </a>
                            <a href="https://vk.com/dualshopqq" class="social-icon social-icon-bw" title="VK" target="_blank" rel="noopener">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="28" height="28" fill="none">
                                    <rect x="1" y="1" width="30" height="30" rx="7" fill="#fff"/>
                                    <rect x="4" y="4" width="24" height="24" rx="6" fill="#222"/>
                                    <path d="M17.7 22C10.7 22 7.1 17.3 7 11H10.1C10.2 15.5 12.3 17.3 13.7 17.6V11H16.6V15.1C18 14.9 19.5 13.3 20 11H22.9C22.6 13.1 21 14.7 19.9 15.4C21 15.9 22.7 17.2 23.3 22H20.1C19.7 19.2 18.2 18.1 17.6 18V22H17.7Z" fill="#fff"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <style>
    .social-icon-bw svg rect:first-child { fill: #fff; }
    .social-icon-bw svg rect:last-child { fill: #222; }
    .social-icon-bw svg path { fill: #fff; }
    .social-icon-bw {
      filter: grayscale(1) contrast(1.1);
      transition: filter 0.2s, box-shadow 0.2s;
      border-radius: 50%;
      box-shadow: 0 2px 8px 0 rgba(0,0,0,0.04);
    }
    .social-icon-bw:hover {
      filter: grayscale(0) brightness(0.8) drop-shadow(0 2px 8px rgba(0,0,0,0.10));
      box-shadow: 0 4px 16px 0 rgba(0,0,0,0.10);
      background: #fff;
    }
    </style>
</html>
<?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>